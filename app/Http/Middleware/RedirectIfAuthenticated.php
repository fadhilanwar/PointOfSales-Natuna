<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            // Jika user ternyata SUDAH LOGIN, tapi nekat buka link /login atau /register
            if (Auth::guard($guard)->check()) {
                
                // Cek rolenya. Jika dia Admin, paksa kembali ke dashboard admin
                if (Auth::user()->role === 'admin') {
                    return redirect('/admin/dashboard');
                }
                
                // Jika dia User biasa, paksa kembali ke halaman beranda/landing page
                return redirect('/');
            }
        }

        // Jika dia BENAR-BENAR BELUM LOGIN (Tamu), persilakan buka halaman login
        return $next($request);
    }
}