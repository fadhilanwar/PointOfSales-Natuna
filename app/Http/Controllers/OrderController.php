<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    // Method Index untuk daftar pesanan (opsional, untuk kelengkapan)
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())->latest()->get();
        return view('orders.index', compact('orders'));
    }

    // Method Show untuk Detail Pesanan (Sesuai Tugas 1)
    public function show(Order $order)
    {
        // Proteksi Keamanan Ke-1: Pastikan pesanan ini milik user yang sedang login
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Akses Ditolak: Anda tidak memiliki izin untuk melihat detail pesanan ini.');
        }

        // Eager Loading: Muat relasi item dan data master produk agar tidak N+1 Query Problem
        $order->load('items.product');

        // Kembalikan ke view
        return view('orders.show', compact('order'));
    }
}
