<?php

namespace App\Http\Middleware;

use Closure;
use App\Utils\Helpers;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::guard('admin')->check()) {
            if (Auth::guard('admin')->user()->status == 0) {
                Auth::guard('admin')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect('login/admin')->with('error', 'Your account has been deactivated. Please contact the administrator.');
            }
            return $next($request);
        } else {
            return redirect('login/admin')->with('error', 'Please log in to access the admin area.');
        }
    }
}
