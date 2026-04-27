<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        // Ambil 8 produk terbaru
        $products = Product::latest()->take(8)->get();

        // Cek pesanan aktif jika user sudah login (Untuk Widget Status Pengiriman)
        $activeOrder = null;
        if (Auth::check()) {
            $activeOrder = Order::where('user_id', Auth::id())
                ->whereIn('status', ['approved', 'shipping'])
                ->with('courier') // Eager load relasi kurir
                ->latest()
                ->first();
        }

        return view('home', compact('products', 'activeOrder'));
    }
}