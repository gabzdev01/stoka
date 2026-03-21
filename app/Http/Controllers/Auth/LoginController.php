<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Shift;
use App\Models\User;

class LoginController extends Controller
{
    public function showLogin()
    {
        if (session()->has("auth_user")) {
            return redirect()->route("dashboard");
        }
        return view("auth.login");
    }

    public function login(Request $request)
    {
        $request->validate([
            "phone" => "required",
            "credential" => "required",
        ]);

        $user = User::where("phone", $request->phone)->first();

        if (!$user) {
            return back()->withErrors(["phone" => "Phone number not found."])->withInput();
        }

        // Owner logs in with password
        if ($user->role === "owner") {
            if (!Hash::check($request->credential, $user->password)) {
                return back()->withErrors(["credential" => "Incorrect password."])->withInput();
            }
        }

        // Staff logs in with PIN
        if ($user->role === "staff") {
            if (!Hash::check($request->credential, $user->pin)) {
                return back()->withErrors(["credential" => "Incorrect PIN."])->withInput();
            }
            if (!$user->active) {
                return back()->withErrors(["phone" => "Your account has been deactivated."])->withInput();
            }
        }

        session([
            "auth_user" => $user->id,
            "auth_role" => $user->role,
            "auth_name" => $user->name,
        ]);

        if ($user->role === "owner") {
            return redirect()->route("dashboard");
        }

        // Restore open shift to session if one exists in the DB
        $openShift = Shift::where("staff_id", $user->id)
            ->where("status", "open")
            ->latest("opened_at")
            ->first();
        if ($openShift) {
            session(["shift_id" => $openShift->id]);
        }

        return redirect()->route("sales.index");
    }

    public function logout(Request $request)
    {
        session()->forget(["auth_user", "auth_role", "auth_name", "shift_id"]);
        return redirect()->route("login");
    }

    public function quickLogin(string $role)
    {
        // Only works on the demo tenant
        if (tenant() === null || tenant()->id !== 'demo') {
            return redirect()->route('login');
        }

        // Map role → demo user
        $userId = match($role) {
            'owner' => 1,  // Maya
            'staff' => 2,  // James
            default  => null,
        };

        if (!$userId) return redirect()->route('login');

        $user = User::find($userId);
        if (!$user) return redirect()->route('login');

        // ── Demo hygiene: collapse multiple open shifts into one ──────────
        // Multiple visitors sharing the same account each create a shift.
        // Keep only the most recent open shift; silently close the rest.
        $openShifts = Shift::where('staff_id', $userId)
            ->where('status', 'open')
            ->orderByDesc('opened_at')
            ->get();

        $canonical = $openShifts->first(); // most recent — keep this one

        if ($openShifts->count() > 1) {
            $stale = $openShifts->skip(1)->pluck('id');
            Shift::whereIn('id', $stale)->update([
                'status'           => 'closed',
                'closed_at'        => now(),
                'cash_counted'     => 0,
                'expected_cash'    => 0,
                'cash_discrepancy' => 0,
                'mpesa_total'      => 0,
                'updated_at'       => now(),
            ]);
        }

        // ── Set auth session ──────────────────────────────────────────────
        session([
            'auth_user' => $user->id,
            'auth_role' => $user->role,
            'auth_name' => $user->name,
        ]);

        // ── Attach to existing open shift (if any) ────────────────────────
        if ($canonical) {
            session(['shift_id' => $canonical->id]);
        }

        // ── Redirect ──────────────────────────────────────────────────────
        return $user->role === 'owner'
            ? redirect()->route('dashboard')
            : redirect()->route('sales.index');
    }
    public function demoLanding(Request $request)
    {
        // Only works on the demo tenant
        if (tenant() === null || tenant()->id !== 'demo') {
            return redirect()->route('login');
        }
        return view('demo.enter');
    }

    public function demoEnter(Request $request)
    {
        if (tenant() === null || tenant()->id !== 'demo') {
            return redirect()->route('login');
        }

        $shopName  = trim($request->input('shop_name', ''));
        $ownerName = trim($request->input('owner_name', ''));
        $role      = in_array($request->input('role'), ['owner','staff']) ? $request->input('role') : 'owner';

        // Store shop name + owner name in session for personalisation
        if ($shopName) {
            session(['demo_shop_name' => $shopName]);
        }
        if ($ownerName) {
            // Extract first name for greeting
            $firstName = explode(' ', $ownerName)[0];
            session(['demo_owner_name' => $firstName]);
        }

        return redirect()->route('quick.login', ['role' => $role]);
    }
}