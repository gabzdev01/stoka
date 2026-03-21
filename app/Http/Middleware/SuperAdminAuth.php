<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SuperAdminAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!session('is_super_admin')) {
            return redirect('/admin/login');
        }
        return $next($request);
    }
}
