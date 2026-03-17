<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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
            if ($request->credential !== $user->pin) {
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

        return redirect()->route("sales.index");
    }

    public function logout(Request $request)
    {
        session()->forget(["auth_user", "auth_role", "auth_name"]);
        return redirect()->route("login");
    }
}
