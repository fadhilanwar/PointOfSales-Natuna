<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Registrant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

// UBAH NAMA CLASS DI SINI
class RegisterController extends Controller 
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'string', 'email', 'max:255', 'unique:users,email', 'unique:registrants,email'],
            'phone'     => ['required', 'string', 'max:20'],
            'shop_name' => ['nullable', 'string', 'max:255'],
            'password'  => ['required', 'string', 'min:8', 'confirmed'], 
        ], [
            'email.unique' => 'Email ini sudah terdaftar atau sedang dalam antrean persetujuan admin.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.'
        ]);

        Registrant::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'phone'     => $request->phone,
            'shop_name' => $request->shop_name,
            'password'  => Hash::make($request->password),
        ]);

        return redirect()->route('login')->with('success', 'Pendaftaran berhasil! Akun Anda sedang direview oleh Admin.');
    }
}