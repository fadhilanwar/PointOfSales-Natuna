<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;

class DebtController extends Controller
{
    /**
     * Menampilkan daftar semua user yang memiliki hutang
     */
    public function index()
    {
        // 1. Ambil data user yang HANYA memiliki pesanan berstatus 'belum_lunas'
        // whereHas digunakan untuk memfilter user berdasarkan tabel relasinya (orders)
        $users = User::whereHas('orders', function ($query) {
            $query->where('payment_status', 'belum_lunas');
        })->get();

        // 2. Hitung total hutang dari masing-masing user menggunakan perulangan sederhana
        foreach ($users as $user) {
            $totalHutangUser = 0;

            // Ambil semua pesanan milik user ini yang masih ngutang
            $orders = Order::where('user_id', $user->id)
                ->where('payment_status', 'belum_lunas')
                ->get();

            // Hitung sisa hutang per pesanan (memanggil accessor remaining_debt di Model Order)
            foreach ($orders as $order) {
                $totalHutangUser += $order->remaining_debt;
            }

            // Simpan nilai total hutang ke dalam variabel dinamis baru milik user
            // agar bisa dipanggil di view blade dengan $user->total_hutang
            $user->total_hutang = $totalHutangUser;
        }

        // 3. Opsional: Filter lagi untuk berjaga-jaga (Hanya tampilkan yang total hutangnya > 0)
        // Kadang ada pesanan berstatus belum_lunas tapi sebenarnya uangnya pas (menunggu ACC admin)
        $users = $users->filter(function ($user) {
            return $user->total_hutang > 0;
        });

        return view('admin.pages.debts.index', compact('users'));
    }

    /**
     * Menampilkan detail hutang (daftar transaksi/pesanan) per User
     */
    public function show($id)
    {
        // 1. Cari data usernya, jika tidak ada munculkan 404
        $user = User::findOrFail($id);

        // 2. Ambil daftar pesanan yang belum lunas milik user tersebut
        $orders = Order::where('user_id', $user->id)
            ->where('payment_status', 'belum_lunas')
            ->latest()
            ->get();

        // 3. Hitung total hutang keseluruhan user ini untuk ditampilkan di atas tabel
        $totalHutang = 0;
        foreach ($orders as $order) {
            $totalHutang += $order->remaining_debt;
        }

        return view('admin.pages.debts.show', compact('user', 'orders', 'totalHutang'));
    }
}
