<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('profile.index', compact('user'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        // Validasi Ketat (Termasuk Foto)
        $validated = $request->validate([
            'profile_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'], // Max 2MB
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'username'      => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'shop_name'     => ['nullable', 'string', 'max:255'],
            'address'       => ['nullable', 'string'],
        ], [
            'profile_photo.image' => 'File harus berupa gambar.',
            'profile_photo.mimes' => 'Format foto harus berupa JPG atau PNG.',
            'profile_photo.max'   => 'Ukuran foto maksimal adalah 2MB.',
            'name.required'       => 'Nama lengkap wajib diisi.',
            'email.required'      => 'Email tidak boleh kosong.',
            'email.email'         => 'Format email tidak valid.',
            'email.unique'        => 'Email ini sudah terdaftar pada akun lain.',
            'username.required'   => 'Username tidak boleh kosong.',
            'username.unique'     => 'Username ini sudah dipakai, coba yang lain.',
        ]);

        // Logika Upload Foto
        if ($request->hasFile('profile_photo')) {
            // Hapus foto lama jika ada
            if ($user->profile_photo_path && Storage::disk('public')->exists($user->profile_photo_path)) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }

            // Simpan foto baru dan tambahkan path-nya ke array tervalidasi
            $validated['profile_photo_path'] = $request->file('profile_photo')->store('profile_photos', 'public');
        }

        // Hapus array profile_photo agar tidak error saat mass assignment (karena kolomnya adalah profile_photo_path)
        unset($validated['profile_photo']);

        $user->update($validated);

        return redirect()->route('profile.index')
            ->with('success', 'Profil tokomu berhasil diperbarui!');
    }

    public function editPassword()
    {
        return view('profile.password');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'current_password.required'         => 'Password saat ini wajib diisi.',
            'current_password.current_password' => 'Password saat ini tidak sesuai dengan catatan kami.',
            'password.required'                 => 'Password baru wajib diisi.',
            'password.min'                      => 'Password baru harus memiliki minimal 8 karakter.',
            'password.confirmed'                => 'Konfirmasi password tidak cocok dengan password baru.',
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('profile.index')
            ->with('success', 'Password berhasil diperbarui!');
    }
}
