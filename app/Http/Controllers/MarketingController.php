<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Artisan;
use App\Mail\TenantWelcome;
use Illuminate\Support\Facades\Mail;

class MarketingController extends Controller
{
    private function articles(): array
    {
        return \Illuminate\Support\Facades\DB::table('articles')
            ->where('published', true)
            ->orderBy('sort_order')
            ->get()
            ->map(fn($a) => (array) $a)
            ->toArray();
    }

    private function testimonial(): ?object
    {
        return \Illuminate\Support\Facades\DB::table('testimonials')
            ->where('published', true)
            ->first();
    }


    public function index()
    {
        $testimonial = $this->testimonial();
        return view('marketing.index', compact('testimonial'));
    }

    public function insights()
    {
        $articles = $this->articles();
        return view('marketing.insights', compact('articles'));
    }

    public function insight(string $slug)
    {
        $articles = $this->articles();
        $article = collect($articles)->firstWhere('slug', $slug);

        if (!$article) {
            abort(404);
        }

        $others = collect($articles)->filter(fn($a) => $a['slug'] !== $slug)->values()->all();
        return view('marketing.insight', compact('article', 'others'));
    }

    public function registerForm()
    {
        return view('marketing.register');
    }

    public function registerWelcome()
    {
        if (!session('welcome')) {
            return redirect('/register');
        }
        return view('marketing.welcome');
    }

    public function register(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'shop_name'  => 'required|string|max:100',
            'owner_name' => 'required|string|max:100',
            'phone'      => 'required|string|max:30',
            'email'      => 'nullable|email|max:100',
            'city'       => 'nullable|string|max:100',
        ]);

        // Derive subdomain slug from shop name
        $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '', $request->shop_name));
        $slug = substr($slug, 0, 28);
        if (strlen($slug) < 3) $slug = 'shop' . rand(100, 999);

        // Ensure uniqueness
        $base = $slug;
        $n    = 1;
        while (\App\Models\Tenant::find($slug) ||
               \Stancl\Tenancy\Database\Models\Domain::where('domain', $slug)->exists()) {
            $slug = $base . $n++;
        }

        // Store inquiry regardless of provisioning outcome
        \Illuminate\Support\Facades\DB::table('registration_inquiries')->insert([
            'shop_name'  => $request->shop_name,
            'owner_name' => $request->owner_name,
            'phone'      => $request->phone,
            'email'      => $request->email,
            'city'       => $request->city,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $password = ucfirst(\Illuminate\Support\Str::random(5)) . rand(100, 999);
        $shopUrl  = 'https://' . $slug . '.stoka.co.ke';

        try {
            // 1. Create tenant
            $tenant = \App\Models\Tenant::create([
                'id'             => $slug,
                'name'           => $request->shop_name,
                'owner_name'     => $request->owner_name,
                'owner_phone'    => $request->phone,
                'owner_whatsapp' => $request->phone,
                'plan'           => 'basic',
                'currency'       => 'KES',
                'status'         => 'active',
                'default_low_stock_threshold' => 3,
                'notify_shift_close'    => true,
                'notify_low_stock'      => true,
                'notify_credit_overdue' => true,
                'receipt_digital'       => true,
                'receipt_print'         => false,
            ]);

            // 2. Create domain
            $tenant->domains()->create(['domain' => $slug]);

            // 3. Run migrations
            \Illuminate\Support\Facades\Artisan::call('tenants:migrate', ['--tenants' => [$slug]]);

            // 4. Create owner user in tenant DB
            tenancy()->initialize($tenant);
            \App\Models\User::create([
                'name'     => $request->owner_name,
                'phone'    => $request->phone,
                'password' => bcrypt($password),
                'role'     => 'owner',
            ]);
            tenancy()->end();

            $provisioned = true;

            // Send welcome email (only if email provided and provisioned successfully)
            if ($request->email) {
                try {
                    Mail::to($request->email)->send(
                        new TenantWelcome($request->shop_name, $request->owner_name, $shopUrl, $request->phone, $password)
                    );
                } catch (\Exception $e) {
                    // Non-blocking - log but don't fail registration
                    \Illuminate\Support\Facades\Log::error('Welcome email failed', ['error' => $e->getMessage()]);
                }
            }

        } catch (\Exception $e) {
            try { tenancy()->end(); } catch (\Exception $e2) {}
            $provisioned = false;
            
            // Alert Stoka team about provisioning failure
            \Illuminate\Support\Facades\Log::error('Tenant provisioning failed', [
                'shop_name' => $request->shop_name,
                'slug' => $slug,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }

        return redirect('/register/welcome')->with('welcome', [
            'shop_name'   => $request->shop_name,
            'owner_name'  => $request->owner_name,
            'shop_url'    => $shopUrl,
            'phone'       => $request->phone,
            'password'    => $password,
            'provisioned' => $provisioned,
        ]);
    }
}
