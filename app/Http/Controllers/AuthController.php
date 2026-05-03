<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Menampilkan halaman login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Memproses Login
    public function login(Request $request)
    {
        // 1. Validasi Input Dasar
        $request->validate([
            'login_id' => 'required|string', // Bisa email atau username
            'password' => 'required|string',
        ], [
            'login_id.required' => 'Email atau Username wajib diisi.',
            'password.required' => 'Password wajib diisi.'
        ]);

        // 2. Cerdas menentukan Field Login (Email / Username)
        $loginType = filter_var($request->login_id, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        // 3. Siapkan Kredensial
        $credentials = [
            $loginType => $request->login_id,
            'password' => $request->password
        ];

        // 4. Proses Autentikasi
        $remember = $request->has('remember'); // Jika ada checkbox remember me

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // 5. Role-based Redirect
            if (Auth::user()->role === 'admin') {
                return redirect()->intended('/admin/dashboard');
            }

            return redirect()->intended('/');
        }

        // Jika Gagal, kembalikan dengan pesan error
        return back()->withErrors([
            'login_id' => 'Kredensial yang Anda masukkan tidak sesuai dengan data kami.',
        ])->onlyInput('login_id');
    }

    // Memproses Logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
