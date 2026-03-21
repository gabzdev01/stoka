<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class OwnerOnly
{
    public function handle(Request $request, Closure $next)
    {
        if (!session()->has("auth_user")) {
            // For demo tenant, redirect to /demo landing instead of /login
            if (tenant() && tenant()->id === 'demo') {
                return redirect('/demo');
            }
            return redirect()->route("login");
        }
        if (session("auth_role") !== "owner") {
            return redirect()->route("sales.index");
        }
        return $next($request);
    }
}
