<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
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
        $tenants = Tenant::orderByDesc('created_at')->get()->map(function ($t) {
            return (object) [
                'id'         => $t->id,
                'name'       => $t->name,
                'owner_name' => $t->owner_name,
                'owner_phone'=> $t->owner_phone,
                'plan'       => $t->plan ?? 'basic',
                'status'     => $t->status ?? 'active',
                'currency'   => $t->currency ?? 'KES',
                'created_at' => $t->created_at,
                'domain'     => optional($t->domains->first())->domain,
            ];
        });

        $stats = [
            'total'     => $tenants->count(),
            'active'    => $tenants->where('status', 'active')->count(),
            'suspended' => $tenants->where('status', 'suspended')->count(),
        ];

        return view('admin.dashboard', compact('tenants', 'stats'));
    }

    // ── Create tenant form ────────────────────────────────────────────
    public function createForm()
    {
        return view('admin.create-tenant');
    }

    // ── Store new tenant ──────────────────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'shop_name'   => 'required|string|max:80',
            'owner_name'  => 'required|string|max:80',
            'owner_phone' => 'required|string|max:20',
            'subdomain'   => ['required','string','max:30','regex:/^[a-z0-9\-]+$/','not_in:www,admin,api,app,mail,stoka,demo'],
            'currency'    => 'required|in:KES,UGX,TZS,RWF,ETB',
            'plan'        => 'required|in:basic,pro',
        ]);

        $slug = strtolower(trim($request->subdomain));

        // Check subdomain not already taken
        if (Tenant::find($slug) || \Stancl\Tenancy\Database\Models\Domain::where('domain', $slug)->exists()) {
            return back()->withInput()->withErrors(['subdomain' => 'This subdomain is already taken.']);
        }

        // Generate a clean memorable password
        $password = ucfirst(Str::random(5)) . rand(100, 999);

        try {
            // 1. Create tenant in central DB
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
                'notify_shift_close'  => true,
                'notify_low_stock'    => true,
                'notify_credit_overdue' => true,
                'receipt_digital'     => true,
                'receipt_print'       => false,
            ]);

            // 2. Create domain
            $tenant->domains()->create(['domain' => $slug]);

            // 3. Run migrations for this tenant
            Artisan::call('tenants:migrate', ['--tenants' => [$slug]]);

            // 4. Create owner user in tenant DB
            tenancy()->initialize($tenant);
            \App\Models\User::create([
                'name'     => $request->owner_name,
                'phone'    => $request->owner_phone,
                'password' => bcrypt($password),
                'role'     => 'owner',
            ]);
            tenancy()->end();

        } catch (\Exception $e) {
            // Clean up if something failed
            try { tenancy()->end(); } catch (\Exception $e2) {}
            return back()->withInput()->withErrors(['subdomain' => 'Setup failed: ' . $e->getMessage()]);
        }

        // Build the WhatsApp onboarding message
        $appUrl = config('app.url');
        $baseHost = parse_url($appUrl, PHP_URL_HOST);
        $shopUrl  = 'http://' . $slug . '.' . $baseHost;

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

    // ── Toggle suspend/active ─────────────────────────────────────────
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
