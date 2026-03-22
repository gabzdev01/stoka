<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index()
    {
        $user = User::find(session('auth_user'));
        return view('profile.index', compact('user'));
    }

    public function updatePin(Request $request)
    {
        $request->validate([
            'current_pin' => 'required|string',
            'new_pin'     => 'required|string|digits_between:4,6|confirmed',
        ]);

        $user = User::find(session('auth_user'));

        if ($request->current_pin !== $user->pin) {
            return back()->with('err_pin', 'Current PIN is incorrect.');
        }

        $user->update(['pin' => $request->new_pin]);

        return back()->with('ok_pin', 'PIN changed successfully.');
    }
}
