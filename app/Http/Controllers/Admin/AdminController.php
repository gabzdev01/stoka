<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    // ── Login ─────────────────────────────────────────────────────────
    public function loginForm()
    {
        if (session('is_super_admin')) return redirect('/admin');
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate(['password' => 'required']);
        $correct = env('SUPER_ADMIN_PASSWORD', 'changeme');
        if ($request->password !== $correct) {
            return back()->withErrors(['password' => 'Incorrect password.']);
        }
        session(['is_super_admin' => true]);
        return redirect('/admin');
    }

    public function logout()
    {
        session()->forget('is_super_admin');
        return redirect('/admin/login');
    }

    // ── Dashboard ─────────────────────────────────────────────────────
    public function dashboard()
    {
        $tenants = Tenant::orderByDesc('created_at')->get();

        $enriched = $tenants->map(function ($t) {
            $data = [
                'id'           => $t->id,
                'name'         => $t->name,
                'owner_name'   => $t->owner_name,
                'owner_phone'  => $t->owner_phone,
                'plan'         => $t->plan ?? 'basic',
                'status'       => $t->status ?? 'active',
                'shop_enabled' => (bool) $t->shop_enabled,
                'currency'     => $t->currency ?? 'KES',
                'created_at'   => $t->created_at,
                'domain'       => optional($t->domains->first())->domain,
                'last_shift_at'     => null,
                'sales_this_month'  => 0,
                'shifts_this_month' => 0,
                'owner_last_seen'   => null,
                'product_count'     => 0,
                'open_credit'       => 0,
                'health'            => 'cold',
            ];

            if ($t->id === 'demo') {
                $data['health'] = 'active';
                return (object) $data;
            }

            try {
                tenancy()->initialize($t);

                $lastShift = DB::table('shifts')
                    ->where('status', 'closed')
                    ->max('closed_at');
                $data['last_shift_at'] = $lastShift ? Carbon::parse($lastShift) : null;

                $monthStart = now()->startOfMonth();
                $data['shifts_this_month'] = DB::table('shifts')
                    ->where('status', 'closed')
                    ->where('closed_at', '>=', $monthStart)
                    ->count();

                $data['sales_this_month'] = (int) DB::table('sales')
                    ->whereNull('voided_at')
                    ->where('created_at', '>=', $monthStart)
                    ->sum('total');

                $data['owner_last_seen'] = DB::table('users')
                    ->where('role', 'owner')
                    ->value('dashboard_last_seen');

                $data['product_count'] = DB::table('products')
                    ->where('status', 'active')
                    ->count();

                $data['open_credit'] = (int) DB::table('credit_ledger')
                    ->where('status', 'open')
                    ->sum('balance');

            } catch (\Exception $e) {
                // Tenant DB unavailable — show dashes, don't crash
            } finally {
                try { tenancy()->end(); } catch (\Exception $e) {}
            }

            // Compute health
            if ($data['last_shift_at']) {
                $daysSince = (int) $data['last_shift_at']->diffInDays(now());
                if ($daysSince <= 7)       $data['health'] = 'active';
                elseif ($daysSince <= 30)  $data['health'] = 'drifting';
                else                       $data['health'] = 'cold';
            } else {
                $data['health'] = 'cold';
            }

            return (object) $data;
        });

        $stats = [
            'total'          => $enriched->count(),
            'active'         => $enriched->where('health', 'active')->count(),
            'drifting'       => $enriched->where('health', 'drifting')->count(),
            'cold'           => $enriched->where('health', 'cold')->count(),
            'inquiries'      => DB::table('registration_inquiries')->count(),
            'demo_today'     => DB::table('demo_visits')->whereDate('created_at', today())->count(),
            'demo_week'      => DB::table('demo_visits')->where('created_at', '>=', now()->subDays(7))->count(),
            'demo_last_visit'=> DB::table('demo_visits')->max('created_at'),
        ];

        return view('admin.dashboard', compact('enriched', 'stats'));
    }

    // ── Inquiries ─────────────────────────────────────────────────────
    public function inquiries()
    {
        $inquiries = DB::table('registration_inquiries')
            ->orderByDesc('created_at')
            ->get();

        $tenantPhones = Tenant::pluck('owner_phone')->toArray();

        $inquiries = $inquiries->map(function ($inq) use ($tenantPhones) {
            $inq->converted = in_array($inq->phone, $tenantPhones);
            return $inq;
        });

        $stats = [
            'total'     => $inquiries->count(),
            'converted' => $inquiries->where('converted', true)->count(),
            'pending'   => $inquiries->where('converted', false)->count(),
        ];

        return view('admin.inquiries', compact('inquiries', 'stats'));
    }

    // ── Demo Visits ───────────────────────────────────────────────────
    public function demoVisits()
    {
        $visits = DB::table('demo_visits')->orderByDesc('created_at')->limit(200)->get();

        $stats = [
            'today'  => DB::table('demo_visits')->whereDate('created_at', today())->count(),
            'week'   => DB::table('demo_visits')->where('created_at', '>=', now()->subDays(7))->count(),
            'total'  => DB::table('demo_visits')->count(),
            'owners' => DB::table('demo_visits')->where('role', 'owner')->count(),
            'staff'  => DB::table('demo_visits')->where('role', 'staff')->count(),
        ];

        $topShopNames = DB::table('demo_visits')
            ->select('shop_name', DB::raw('count(*) as count'))
            ->whereNotNull('shop_name')
            ->where('shop_name', '!=', '')
            ->groupBy('shop_name')
            ->orderByDesc('count')
            ->limit(20)
            ->get();

        return view('admin.demo-visits', compact('visits', 'stats', 'topShopNames'));
    }

    // ── Tenant Detail ─────────────────────────────────────────────────
    public function tenantDetail(string $id)
    {
        $tenant = Tenant::findOrFail($id);
        $detail = [
            'shifts'        => collect(),
            'monthly_sales' => collect(),
            'staff'         => collect(),
            'products'      => ['active' => 0, 'inactive' => 0],
            'open_credit'   => 0,
            'owner_last_seen' => null,
            'total_sales'   => 0,
            'total_shifts'  => 0,
        ];

        try {
            tenancy()->initialize($tenant);

            $detail['shifts'] = DB::table('shifts')
                ->join('users', 'users.id', '=', 'shifts.staff_id')
                ->select('shifts.*', 'users.name as staff_name')
                ->where('shifts.status', 'closed')
                ->orderByDesc('shifts.closed_at')
                ->limit(30)
                ->get();

            // Monthly sales last 6 months
            $monthly = [];
            for ($i = 5; $i >= 0; $i--) {
                $month = now()->subMonths($i);
                $total = (int) DB::table('sales')
                    ->whereNull('voided_at')
                    ->whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->sum('total');
                $monthly[] = (object)[
                    'month' => $month->format('M Y'),
                    'total' => $total,
                ];
            }
            $detail['monthly_sales'] = collect($monthly);

            $detail['staff'] = DB::table('users')
                ->where('role', 'staff')
                ->get();

            $detail['products'] = [
                'active'   => DB::table('products')->where('status', 'active')->count(),
                'inactive' => DB::table('products')->where('status', 'inactive')->count(),
            ];

            $detail['open_credit'] = (int) DB::table('credit_ledger')
                ->where('status', 'open')
                ->sum('balance');

            $detail['owner_last_seen'] = DB::table('users')
                ->where('role', 'owner')
                ->value('dashboard_last_seen');

            $detail['total_sales'] = (int) DB::table('sales')
                ->whereNull('voided_at')
                ->sum('total');

            $detail['total_shifts'] = DB::table('shifts')
                ->where('status', 'closed')
                ->count();

        } catch (\Exception $e) {
            // DB unavailable
        } finally {
            try { tenancy()->end(); } catch (\Exception $e) {}
        }

        $maxSale = $detail['monthly_sales']->max('total') ?: 1;

        return view('admin.tenant-detail', compact('tenant', 'detail', 'maxSale'));
    }

    // ── Shop Toggle ───────────────────────────────────────────────────
    public function shopToggle(string $id)
    {
        $tenant = Tenant::findOrFail($id);
        $tenant->update(['shop_enabled' => !$tenant->shop_enabled]);
        return back()->with('toggled_shop', [
            'name'    => $tenant->name,
            'enabled' => $tenant->shop_enabled,
        ]);
    }

    // ── Reset Password ────────────────────────────────────────────────
    public function resetPassword(string $id)
    {
        if ($id === 'demo') return back()->with('error', 'Cannot reset demo tenant password.');

        $tenant   = Tenant::findOrFail($id);
        $password = ucfirst(Str::random(5)) . rand(100, 999);

        try {
            tenancy()->initialize($tenant);
            $owner = DB::table('users')->where('role', 'owner')->first();
            if ($owner) {
                DB::table('users')->where('id', $owner->id)
                    ->update(['password' => Hash::make($password)]);
            }
        } catch (\Exception $e) {
            try { tenancy()->end(); } catch (\Exception $e2) {}
            return back()->with('error', 'Could not reset password: ' . $e->getMessage());
        } finally {
            try { tenancy()->end(); } catch (\Exception $e) {}
        }

        $waMessage = "Hi {$tenant->owner_name} 👋\n\n"
            . "Your Stoka password for *{$tenant->name}* has been reset.\n\n"
            . "🔑 New password: {$password}\n"
            . "🔗 Login: https://{$id}.stoka.co.ke\n\n"
            . "Let me know if you need anything. — Stoka";

        return back()->with('reset_password', [
            'shop'       => $tenant->name,
            'owner'      => $tenant->owner_name,
            'phone'      => $tenant->owner_phone,
            'password'   => $password,
            'wa_message' => $waMessage,
        ]);
    }

    // ── Create Tenant Form ────────────────────────────────────────────
    public function createForm()
    {
        return view('admin.create-tenant');
    }

    // ── Store New Tenant ──────────────────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'shop_name'   => 'required|string|max:80',
            'owner_name'  => 'required|string|max:80',
            'owner_phone' => 'required|string|max:20',
            'subdomain'   => ['required','string','max:30','regex:/^[a-z0-9\\-]+$/','not_in:www,admin,api,app,mail,stoka,demo'],
            'currency'    => 'required|in:KES,UGX,TZS,RWF,ETB',
            'plan'        => 'required|in:basic,pro',
        ]);

        $slug = strtolower(trim($request->subdomain));

        if (Tenant::find($slug) || \Stancl\Tenancy\Database\Models\Domain::where('domain', $slug)->exists()) {
            return back()->withInput()->withErrors(['subdomain' => 'This subdomain is already taken.']);
        }

        $password = ucfirst(Str::random(5)) . rand(100, 999);

        try {
            $tenant = Tenant::create([
                'id'             => $slug,
                'name'           => $request->shop_name,
                'owner_name'     => $request->owner_name,
                'owner_phone'    => $request->owner_phone,
                'owner_whatsapp' => $request->owner_phone,
                'plan'           => $request->plan,
                'currency'       => $request->currency,
                'status'         => 'active',
                'default_low_stock_threshold' => 3,
                'notify_shift_close'    => true,
                'notify_low_stock'      => true,
                'notify_credit_overdue' => true,
                'receipt_digital'       => true,
                'receipt_print'         => false,
            ]);

            $tenant->domains()->create(['domain' => $slug]);
            Artisan::call('tenants:migrate', ['--tenants' => [$slug]]);

            tenancy()->initialize($tenant);
            \App\Models\User::create([
                'name'     => $request->owner_name,
                'phone'    => $request->owner_phone,
                'password' => bcrypt($password),
                'role'     => 'owner',
            ]);
            tenancy()->end();

        } catch (\Exception $e) {
            try { tenancy()->end(); } catch (\Exception $e2) {}
            return back()->withInput()->withErrors(['subdomain' => 'Setup failed: ' . $e->getMessage()]);
        }

        $shopUrl   = 'https://' . $slug . '.stoka.co.ke';
        $waMessage = "Hi {$request->owner_name} 👋\n\n"
            . "Your Stoka account for *{$request->shop_name}* is ready!\n\n"
            . "🔗 Login: {$shopUrl}\n"
            . "📞 Phone: {$request->owner_phone}\n"
            . "🔑 Password: {$password}\n\n"
            . "You can change your password from Settings after logging in.\n\n"
            . "I'm here if you need anything. — Stoka";

        return redirect('/admin')->with('created', [
            'shop_name'   => $request->shop_name,
            'shop_url'    => $shopUrl,
            'owner_name'  => $request->owner_name,
            'owner_phone' => $request->owner_phone,
            'password'    => $password,
            'wa_message'  => $waMessage,
        ]);
    }

    // ── Toggle Suspend/Active ─────────────────────────────────────────
    public function toggle(string $id)
    {
        $tenant = Tenant::findOrFail($id);
        if ($tenant->id === 'demo') {
            return back()->with('error', 'Cannot suspend the demo tenant.');
        }
        $tenant->update([
            'status' => $tenant->status === 'active' ? 'suspended' : 'active',
        ]);
        return back()->with('toggled', $tenant->name);
    }
}
