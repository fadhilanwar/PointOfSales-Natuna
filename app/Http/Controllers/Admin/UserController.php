<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // 1. Menampilkan daftar pengguna
    public function index()
    {
        $users = User::latest()->get();
        return view('admin.pages.users.index', compact('users'));
    }

    // 2. Menampilkan form tambah pengguna
    public function create()
    {
        return view('admin.pages.users.create');
    }

    // 3. Memproses penyimpanan data pengguna baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,user',
            'shop_name' => 'nullable|string|max:255',
            // VALIDASI BARU: Menggunakan regex untuk membatasi karakter
            'phone_number' => ['nullable', 'string', 'max:20', 'regex:/^[0-9\+\-]+$/'],
            'address' => 'nullable|string',
        ], [
            // (Opsional) Pesan error kustom agar user paham letak kesalahannya
            'phone_number.regex' => 'Nomor telepon hanya boleh berisi angka, tanda plus (+), dan setrip (-).'
        ]);

        $data = $request->all();
        // Enkripsi password menggunakan Hash bawaan Laravel
        $data['password'] = Hash::make($request->password);

        User::create($data);

        if ($request->filled('redirect_to') && $request->redirect_to === 'pos') {
            return redirect()->route('admin.pos.index')->with('success', 'Pelanggan baru berhasil ditambahkan! Silakan pilih namanya di kolom pembeli.');
        }

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil ditambahkan!');
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            // Validasi unique diabaikan untuk ID user yang sedang diedit
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6', // Password boleh kosong saat update
            'role' => 'required|in:admin,user',
            'shop_name' => 'nullable|string|max:255',
            // VALIDASI BARU: Terapkan regex yang sama pada proses update
            'phone_number' => ['nullable', 'string', 'max:20', 'regex:/^[0-9\+\-]+$/'],
            'address' => 'nullable|string',
        ], [
            // (Opsional) Pesan error kustom
            'phone_number.regex' => 'Nomor telepon hanya boleh berisi angka, tanda plus (+), dan setrip (-).'
        ]);

        $data = $request->all();

        // Cek apakah kolom password diisi, jika ya enkripsi, jika tidak buang dari array agar password lama tidak tertimpa null
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'Data pengguna berhasil diperbarui!');
    }

    // 4. Menampilkan form edit pengguna
    public function edit(User $user)
    {
        return view('admin.pages.users.edit', compact('user'));
    }

    // 5. Memproses pembaruan data pengguna

    // 6. Menghapus data pengguna
    public function destroy(User $user)
    {
        // Opsional: Mencegah admin yang sedang login menghapus akunnya sendiri
        if (auth()->id() === $user->id) {
            return redirect()->route('admin.users.index')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dihapus!');
    }
}
