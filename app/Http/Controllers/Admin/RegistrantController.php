<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Registrant;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class RegistrantController extends Controller
{
    public function index()
    {
        $registrants = Registrant::latest()->get();
        return view('admin.pages.registrants.index', compact('registrants'));
    }

    public function approve($id)
    {
        DB::beginTransaction();
        
        try {
            $registrant = Registrant::findOrFail($id);

            // Cek email ganda
            $existingUser = User::where('email', $registrant->email)->first();
            if ($existingUser) {
                $registrant->delete();
                DB::commit();
                return redirect()->back()->with('error', 'Gagal! Email sudah terdaftar.');
            }

            // --- BUAT USERNAME OTOMATIS DARI EMAIL ---
            // Ambil kata sebelum '@' pada email
            $baseUsername = explode('@', $registrant->email)[0];
            $username = $baseUsername;
            
            // Cek apakah username tersebut sudah dipakai orang lain, jika ya tambahkan angka
            $counter = 1;
            while (User::where('username', $username)->exists()) {
                $username = $baseUsername . $counter;
                $counter++;
            }
            // ------------------------------------------

            // Pindahkan data ke tabel users utama
            User::create([
                'name'      => $registrant->name,
                'username'  => $username, // Masukkan username yang digenerate
                'email'     => $registrant->email,
                'password'  => $registrant->password,
                'phone'     => $registrant->phone,
                'shop_name' => $registrant->shop_name,
            ]);

            $registrant->delete();
            DB::commit();

            return redirect()->back()->with('success', 'Berhasil! Akun ' . $registrant->name . ' telah disetujui dengan username: ' . $username);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    public function reject($id)
    {
        $registrant = Registrant::findOrFail($id);
        $registrant->delete();
        return redirect()->back()->with('success', 'Pendaftaran berhasil ditolak dan dihapus.');
    }
}