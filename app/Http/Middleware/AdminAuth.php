<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Cek apakah pengguna sudah login dan memiliki peran admin
        if (!Auth::check() || !Auth::user()->is_admin) {
            // Redirect ke halaman login admin jika tidak
            return redirect('/admin-login');
        }

        return $next($request);
    }
}