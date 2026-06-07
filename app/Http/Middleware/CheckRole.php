<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     * * Parameter $role akan menangkap string yang kita lempar dari web.php
     * Misalnya: 'admin' atau 'user'
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        // 1. Pastikan user sudah login dulu sebelum mengecek role
        if (!Auth::check()) {
            return redirect('/login');
        }

        // 2. Cek apakah role user yang login SAMA dengan role yang diizinkan di rute
        if (Auth::user()->role !== $role) {

            // Jika dia 'user' biasa tapi mencoba masuk ke link '/admin/...'
            if (Auth::user()->role === 'user') {
                return redirect('/')->with('error', 'Akses ditolak! Anda bukan Admin.');
            }

            // Jika dia 'admin' tapi mencoba masuk ke link khusus user (seperti keranjang/checkout)
            if (Auth::user()->role === 'admin') {
                return redirect('/admin/dashboard')->with('error', 'Admin tidak bisa melakukan transaksi.');
            }

            // Jika role tidak dikenali sama sekali, tampilkan halaman 403 Forbidden
            abort(403, 'Akses tidak diizinkan.');
        }

        // 3. Jika rolenya cocok, persilakan user melanjutkan ke Controller yang dituju
        return $next($request);
    }
}
