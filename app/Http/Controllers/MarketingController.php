<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Artisan;

class MarketingController extends Controller
{
    private function articles(): array
    {
        return [
            [
                'slug' => 'the-notebook',
                'title' => 'Your notebook isn\'t lying to you. It just can\'t tell you the truth.',
                'preview' => 'A notebook records what gets written in it. The gap between what moved and what was recorded isn\'t in the notebook — it\'s in everything the notebook never saw.',
                'body' => "You have been using it for years. It sits next to the till, filled with the handwriting of everyone who has ever worked a shift in your shop. It is familiar. It feels like control.\n\nBut here is what it cannot do. It cannot tell you what the float should be before the shift starts. It cannot compare what was expected with what was counted. It cannot send you a summary at 7pm when you are not in the shop. It cannot remember the sale from three Tuesdays ago when a customer comes back with a complaint.\n\nThe notebook records what someone chose to write down. Nothing more. If something was not written, it does not exist. If something was written wrong, it looks right.\n\nThis is not a problem with honesty. Most people who use the notebook are not trying to deceive you. The problem is that the notebook makes accuracy optional. There is no check. There is no confirmation. There is no version that cannot be changed.\n\nYou count the float and it does not match. You look at what was written and it seems fine. You do the subtraction again. The answer is the same. You have no way of knowing what happened because the only record is the one that does not add up.\n\nThis is the moment where most boutique owners absorb the loss, open the shop, and say nothing. Not because they do not care. Because they have nothing to say. The notebook gave them a number. The cash gave them a different number. The system cannot explain the difference.\n\nA system that cannot explain the difference is not protecting you. It is just recording.\n\nStoka replaces the notebook with something that ties every sale to a shift, every shift to a person, and every closing count to an expected number. The moment a shift closes, you can see the difference — if there is one — from wherever you are. Immediately. Without calling anyone.\n\nThe notebook was never the problem. The problem is that it was never designed for accountability. It was designed for memory. Memory is not enough.",
            ],
            [
                'slug' => 'last-tuesday',
                'title' => 'How much did your shop make last Thursday?',
                'preview' => 'The answer is probably in your head as a feeling. The actual number — the one that accounts for everything that moved — is somewhere you cannot easily reach.',
                'body' => "Not roughly. Not what you remember. The actual number — the one that accounts for every sale, every payment method, every return, and every shilling that moved through the till. Can you produce it right now?\n\nMost boutique owners cannot. They have a feeling. They remember it was a decent day, or a slow one. They might have a figure scrawled in a notebook somewhere. But the actual number — verified, broken down, sitting there waiting to be read — does not exist.\n\nThis matters more than it seems.\n\nIf you cannot tell what you made last Thursday, you cannot tell whether this Thursday is better or worse. You cannot see the trend. You cannot know which days of the week your sales peak. You cannot see whether the promotion you ran last month moved anything. You are running a business on instinct and memory, and instinct and memory are not the same as information.\n\nInformation is what you act on. Instinct is what you act on when information is not available.\n\nThe problem is not laziness. It is structure. A notebook does not produce a daily summary. A notebook does not break your sales by payment method so you can see how much M-Pesa versus cash came in. A notebook does not show you the gap between what you expected and what you counted. You would have to do all of that yourself, every day, for every shift, which is work that most people cannot sustain alongside everything else that running a shop requires.\n\nSo the information disappears. And you run on instinct.\n\nStoka produces a shift summary automatically when each shift closes. Date, staff name, total sales, M-Pesa, cash, expected float, actual count, discrepancy. You do not have to calculate it. You do not have to chase anyone for it. It is there.\n\nLast Thursday is there. The Thursday before that is there. Every day for as long as you have been using the system is there, in the same format, comparable, readable.\n\nThat is what it means to actually know what you made. Not a feeling. A number.",
            ],
            [
                'slug' => 'buying-on-instinct',
                'title' => 'The real cost of buying on instinct.',
                'preview' => 'Every restocking trip made without current stock data produces some version of the same outcome. Wrong quantities, wrong sizes, capital sitting instead of moving.',
                'body' => "You go to the supplier and you buy what feels right. The sizes that seemed to sell. The colours that customers asked about. The styles that moved quickly last time. This is how most boutique owners buy. It is also how most boutique owners end up with a rack of things that do not move.\n\nThe feeling is not wrong. The feeling is based on real experience. But experience stored in memory compresses over time. You remember the things that surprised you — the item you did not expect to sell that flew out. You forget the slow movers that sat for six months before you marked them down. The feeling is biased toward the memorable, not the accurate.\n\nThe accurate picture is in the sales data. How many of this item sold in the last 90 days. Which sizes moved and which did not. What your stock level is right now versus what it was three months ago. Which items have not sold a single unit since you bought them.\n\nIf you do not have that picture, you buy on instinct. And instinct costs money — tied up in stock that does not move, money that is not available for things that would.\n\nThere is a second cost that is harder to see. When you run out of the things that actually sell, customers leave and buy from someone else. That sale is gone. That customer may not come back. The cost of being out of stock on the right things is as real as the cost of being overstocked on the wrong ones. You just never see it in the till because it never made it there.\n\nStoka tracks every sale at the product level. You can see what moved this month, what moved last month, what has been sitting. You get a shopping list based on what is actually low, not what you think might be low. You walk into the supplier with a list instead of a feeling.\n\nThe list does not make the buying decision for you. You still know your customers. You still have taste. But the list means you are choosing from a position of knowledge, not guesswork.\n\nThat is the difference between buying well and buying hopefully.",
            ],
            [
                'slug' => 'the-staff-problem',
                'title' => 'The staff problem that is not a staff problem.',
                'preview' => 'Good people in systems that make honesty hard to prove behave like the system allows. The way to change the outcome is not to change the people.',
                'body' => "Good people in broken systems behave like the system allows. The way to change behaviour is not to change the people.\n\nMost boutique owners have had a staff problem at some point. Money that did not add up. Stock that seemed to disappear. A sale that was made but not recorded. The natural response is to suspect the person. To watch more carefully. To feel the slow erosion of trust that comes from not knowing.\n\nBut consider what the system actually offers them. A notebook that can be written in and corrected. A till that has no confirmation step. A float that is counted at the end of the day by the same person who managed it all day. A record that cannot be verified against anything independent.\n\nIn that environment, a small dishonesty is invisible. Not because the person is invisible, but because the system makes the act invisible. There is no trail. There is no comparison. There is nothing that says: this sale happened, this amount was expected, this amount was counted, the difference is this.\n\nChange the system and you change what is possible. Not by accusing anyone. Not by adding cameras or spot checks or the kind of surveillance that destroys the working relationship. By making the numbers automatic and transparent.\n\nWhen staff know that every shift produces a reconciliation that goes to the owner — that the expected cash is calculated before they count, not after — behaviour changes. Not because they were dishonest before. Because the system now makes honesty visible. The record is not something they produce. It is something that happens around them.\n\nThis is not punishment. It is clarity. Staff who are honest appreciate it because it protects them. If there is a discrepancy and they did not cause it, the system shows that. They are not walking into Monday morning with a shortfall they cannot explain and no way to prove their account.\n\nThe staff problem is usually a system problem. Fix the system first. See what remains.",
            ],
            [
                'slug' => 'end-of-day-summary',
                'title' => 'What your end-of-day summary is not telling you.',
                'preview' => 'A verbal summary tells you what your staff member remembers happening. It cannot tell you what the system has no mechanism to capture.',
                'body' => "At the end of the day, you have a number. Maybe written down, maybe in your head. You know roughly how it went. But roughly is doing a lot of work in that sentence.\n\nThe number you have is total sales. Maybe broken into cash and M-Pesa if someone was diligent enough to track it separately. What you do not have is any of the following: the exact amount of cash that should be in the till given the opening float and what was sold; how that compares to what is actually in the till; whether any returns were made and how they were processed; which products were sold; whether stock levels updated correctly; what the running balance is for customers buying on credit.\n\nAll of that happened today. None of it is in the summary you have.\n\nThis is not a small gap. Each of those missing pieces is a place where money can move without accountability. Not dramatically, not all at once. Quietly, in the places where the record does not reach.\n\nThe credit book is a good example. Someone buys on credit. It goes in the notebook. Later, they pay. Maybe that payment goes in the notebook too. Maybe it does not. Maybe it gets recorded against the wrong name. Maybe the notebook gets wet and the entry from two months ago is no longer readable. The debt was real. The record was fragile.\n\nOr consider the return. A customer comes back with something. The staff member takes it and gives them something else or a refund. Does that go in the notebook? Does the original sale get updated? Does the cash or M-Pesa record reflect it? In most boutiques, the answer to all three is: probably not fully.\n\nA proper end-of-day summary is not a number. It is a reconciliation. It tells you what came in, how it came in, what should be in the till, what is in the till, and what the difference is. It is signed against a name. It exists permanently and can be looked at again tomorrow, or six months from now.\n\nThat is what Stoka produces at the close of every shift. Not an estimate. A record.",
            ],
        ];
    }

    public function index()
    {
        return view('marketing.index');
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
