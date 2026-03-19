<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PasswordResetController extends Controller
{
    public function show()
    {
        return view('settings.password-reset');
    }

    public function requestReset(Request $request)
    {
        $request->validate(['phone' => 'required|string']);

        $owner = User::where('role', 'owner')->where('phone', $request->phone)->first();
        if (!$owner) {
            return redirect()->route('password-reset.show')
                ->with('err_reset', 'Phone number not found.');
        }

        $code    = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        $expires = now()->addMinutes(30);

        $tenant = \App\Models\Tenant::find(tenant('id'));
        $tenant->update([
            'password_reset_token'      => $code,
            'password_reset_expires_at' => $expires,
        ]);

        $shopName = tenant('name');
        $msg  = "Your Stoka password reset code for {$shopName} is: {$code}. Valid for 30 minutes. If you did not request this, ignore this message.";
        $waPhone = ltrim(preg_replace('/\D/', '', tenant('owner_whatsapp') ?? $owner->phone), '0');
        if (!str_starts_with($waPhone, '254')) $waPhone = '254' . substr($waPhone, -9);
        $waUrl = 'https://wa.me/' . $waPhone . '?text=' . rawurlencode($msg);

        return redirect()->route('password-reset.show')
            ->with('wa_url', $waUrl)
            ->with('reset_phone', $request->phone);
    }

    public function confirm(Request $request)
    {
        $request->validate([
            'code'         => 'required|string|size:6',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $tenant = \App\Models\Tenant::find(tenant('id'));

        if (!$tenant->password_reset_token || $tenant->password_reset_token !== $request->code) {
            return back()->with('err_confirm', 'That code is incorrect. Please try again.');
        }

        if (now()->isAfter($tenant->password_reset_expires_at)) {
            return back()->with('err_confirm', 'That code has expired. Please request a new one.');
        }

        $owner = User::where('role', 'owner')->first();
        $owner->update(['password' => Hash::make($request->new_password)]);

        $tenant->update([
            'password_reset_token'      => null,
            'password_reset_expires_at' => null,
        ]);

        return redirect()->route('login')
            ->with('success', 'Password reset. Please log in with your new password.');
    }
}
