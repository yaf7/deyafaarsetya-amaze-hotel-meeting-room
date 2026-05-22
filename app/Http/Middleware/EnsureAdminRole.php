<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureAdminRole
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('web')->check() || Auth::guard('web')->user()->role !== 'admin') {
            return redirect()->route('admin.login');
        }
        return $next($request);
    }
}
