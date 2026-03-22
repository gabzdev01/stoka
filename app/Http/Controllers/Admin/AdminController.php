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

    // ── Articles ──────────────────────────────────────────────────────
    public function articles()
    {
        $articles = DB::table('articles')->orderBy('sort_order')->get();
        return view('admin.articles', compact('articles'));
    }

    public function articleCreate()
    {
        return view('admin.article-form', ['article' => null]);
    }

    public function articleStore(Request $request)
    {
        $request->validate(['slug'=>'required|unique:articles,slug','title'=>'required','preview'=>'required','body'=>'required']);
        DB::table('articles')->insert([
            'slug'       => $request->slug,
            'title'      => $request->title,
            'preview'    => $request->preview,
            'body'       => $request->body,
            'published'  => $request->has('published') ? 1 : 0,
            'sort_order' => (int) $request->sort_order,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return redirect()->route('admin.articles')->with('ok', 'Article created.');
    }

    public function articleEdit(int $id)
    {
        $article = DB::table('articles')->find($id);
        if (!$article) abort(404);
        return view('admin.article-form', compact('article'));
    }

    public function articleUpdate(Request $request, int $id)
    {
        $request->validate(['title'=>'required','preview'=>'required','body'=>'required']);
        DB::table('articles')->where('id', $id)->update([
            'title'      => $request->title,
            'preview'    => $request->preview,
            'body'       => $request->body,
            'published'  => $request->has('published') ? 1 : 0,
            'sort_order' => (int) $request->sort_order,
            'updated_at' => now(),
        ]);
        return redirect()->route('admin.articles')->with('ok', 'Article updated.');
    }

    public function articleDelete(int $id)
    {
        DB::table('articles')->where('id', $id)->delete();
        return redirect()->route('admin.articles')->with('ok', 'Article deleted.');
    }

    // ── Testimonials ──────────────────────────────────────────────────
    public function testimonials()
    {
        $testimonials = DB::table('testimonials')->orderByDesc('created_at')->get();
        return view('admin.testimonials', compact('testimonials'));
    }

    public function testimonialCreate()
    {
        return view('admin.testimonial-form', ['testimonial' => null]);
    }

    public function testimonialStore(Request $request)
    {
        $request->validate(['name'=>'required','location'=>'required','pull_quote'=>'required','body'=>'required']);
        DB::table('testimonials')->insert([
            'name'       => $request->name,
            'location'   => $request->location,
            'pull_quote' => $request->pull_quote,
            'body'       => $request->body,
            'published'  => $request->has('published') ? 1 : 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return redirect()->route('admin.testimonials')->with('ok', 'Testimonial added.');
    }

    public function testimonialEdit(int $id)
    {
        $testimonial = DB::table('testimonials')->find($id);
        if (!$testimonial) abort(404);
        return view('admin.testimonial-form', compact('testimonial'));
    }

    public function testimonialUpdate(Request $request, int $id)
    {
        $request->validate(['name'=>'required','location'=>'required','pull_quote'=>'required','body'=>'required']);
        DB::table('testimonials')->where('id', $id)->update([
            'name'       => $request->name,
            'location'   => $request->location,
            'pull_quote' => $request->pull_quote,
            'body'       => $request->body,
            'published'  => $request->has('published') ? 1 : 0,
            'updated_at' => now(),
        ]);
        return redirect()->route('admin.testimonials')->with('ok', 'Testimonial updated.');
    }

    public function testimonialDelete(int $id)
    {
        DB::table('testimonials')->where('id', $id)->delete();
        return redirect()->route('admin.testimonials')->with('ok', 'Testimonial deleted.');
    }

    // ── Demo Products ─────────────────────────────────────────────────
    public function demoProducts()
    {
        $products = collect();
        try {
            $demo = \App\Models\Tenant::find('demo');
            if ($demo) {
                tenancy()->initialize($demo);
                $products = DB::table('products')->orderBy('category')->orderBy('name')->get();
            }
        } catch (\Exception $e) {
        } finally {
            try { tenancy()->end(); } catch (\Exception $e) {}
        }
        return view('admin.demo-products', compact('products'));
    }

    public function demoProductToggle(int $id)
    {
        try {
            $demo = \App\Models\Tenant::find('demo');
            if ($demo) {
                tenancy()->initialize($demo);
                $product = DB::table('products')->find($id);
                if ($product) {
                    DB::table('products')->where('id', $id)->update([
                        'shop_visible' => !$product->shop_visible,
                        'updated_at'   => now(),
                    ]);
                }
            }
        } catch (\Exception $e) {
        } finally {
            try { tenancy()->end(); } catch (\Exception $e) {}
        }
        return back()->with('ok', 'Product updated.');
    }


    // ── Demo Product Create ───────────────────────────────────────────
    public function demoProductCreate()
    {
        return view('admin.demo-product-form', ['product' => null]);
    }

    // ── Demo Product Store ────────────────────────────────────────────
    public function demoProductStore(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:100',
            'shelf_price' => 'required|numeric|min:0',
            'category'    => 'nullable|string|max:60',
            'stock'       => 'required|integer|min:0',
            'photo'       => 'nullable|image|max:4096',
        ]);

        try {
            $demo = \App\Models\Tenant::find('demo');
            tenancy()->initialize($demo);

            $photoPath = null;
            if ($request->hasFile('photo')) {
                $photoPath = $this->storeDemoPhoto($request->file('photo'));
            }

            DB::table('products')->insert([
                'name'         => $request->name,
                'category'     => $request->category,
                'type'         => 'unit',
                'shelf_price'  => $request->shelf_price,
                'stock'        => $request->stock,
                'shop_visible' => $request->has('shop_visible') ? 1 : 0,
                'description'  => $request->description,
                'photo'        => $photoPath,
                'status'       => 'active',
                'track_stock'  => 1,
                'low_stock_threshold' => 3,
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);

        } catch (\Exception $e) {
            try { tenancy()->end(); } catch (\Exception $e2) {}
            return back()->with('error', 'Could not create product: ' . $e->getMessage());
        } finally {
            try { tenancy()->end(); } catch (\Exception $e) {}
        }

        return redirect()->route('admin.demo-products')->with('ok', 'Product added.');
    }

    // ── Demo Product Edit ─────────────────────────────────────────────
    public function demoProductEdit(int $id)
    {
        $product = null;
        try {
            $demo = \App\Models\Tenant::find('demo');
            tenancy()->initialize($demo);
            $product = DB::table('products')->find($id);
        } catch (\Exception $e) {
        } finally {
            try { tenancy()->end(); } catch (\Exception $e) {}
        }
        if (!$product) abort(404);
        return view('admin.demo-product-form', compact('product'));
    }

    // ── Demo Product Update ───────────────────────────────────────────
    public function demoProductUpdate(Request $request, int $id)
    {
        $request->validate([
            'name'        => 'required|string|max:100',
            'shelf_price' => 'required|numeric|min:0',
            'category'    => 'nullable|string|max:60',
            'stock'       => 'required|integer|min:0',
            'photo'       => 'nullable|image|max:4096',
        ]);

        try {
            $demo = \App\Models\Tenant::find('demo');
            tenancy()->initialize($demo);

            $product   = DB::table('products')->find($id);
            $photoPath = $product->photo ?? null;

            if ($request->hasFile('photo')) {
                // Delete old photo
                if ($photoPath) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($photoPath);
                }
                $photoPath = $this->storeDemoPhoto($request->file('photo'));
            }

            if ($request->input('remove_photo') === '1' && $photoPath) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($photoPath);
                $photoPath = null;
            }

            DB::table('products')->where('id', $id)->update([
                'name'         => $request->name,
                'category'     => $request->category,
                'shelf_price'  => $request->shelf_price,
                'stock'        => $request->stock,
                'shop_visible' => $request->has('shop_visible') ? 1 : 0,
                'description'  => $request->description,
                'photo'        => $photoPath,
                'updated_at'   => now(),
            ]);

        } catch (\Exception $e) {
            try { tenancy()->end(); } catch (\Exception $e2) {}
            return back()->with('error', 'Could not update: ' . $e->getMessage());
        } finally {
            try { tenancy()->end(); } catch (\Exception $e) {}
        }

        return redirect()->route('admin.demo-products')->with('ok', 'Product updated.');
    }

    // ── Demo Product Delete ───────────────────────────────────────────
    public function demoProductDelete(int $id)
    {
        try {
            $demo = \App\Models\Tenant::find('demo');
            tenancy()->initialize($demo);
            $product = DB::table('products')->find($id);
            if ($product && $product->photo) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($product->photo);
            }
            DB::table('products')->where('id', $id)->delete();
        } catch (\Exception $e) {
        } finally {
            try { tenancy()->end(); } catch (\Exception $e) {}
        }
        return redirect()->route('admin.demo-products')->with('ok', 'Product deleted.');
    }

    // ── Store demo photo (shared helper) ─────────────────────────────
    private function storeDemoPhoto($file): string
    {
        $manager    = new \Intervention\Image\ImageManager(new \Intervention\Image\Drivers\Gd\Driver());
        $image      = $manager->read($file->getRealPath());
        if ($image->width() > 1200) {
            $image->scaleDown(width: 1200);
        }
        $compressed = $image->toWebp(quality: 82)->toString();
        $filename   = 'products/demo/' . uniqid('p_', true) . '.webp';
        \Illuminate\Support\Facades\Storage::disk('public')->put($filename, $compressed);
        return $filename;
    }

}
