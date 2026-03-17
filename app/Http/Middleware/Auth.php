<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Auth
{
    public function handle(Request $request, Closure $next)
    {
        if (!session()->has("auth_user")) {
            return redirect()->route("login");
        }
        return $next($request);
    }
}
