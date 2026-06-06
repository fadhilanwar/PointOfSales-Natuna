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

    /**
     * Menampilkan detail produk berdasarkan slug (Otomatis ditangani oleh Route Model Binding)
     */
    public function show(Product $product)
    {
        // loadMissing() lebih efisien daripada load(). 
        // Hanya akan melakukan query jika relasi 'category' belum dimuat.
        $product->loadMissing('category');

        // Ambil 4 produk serupa (Cross-selling). 
        // Menggunakan ternary/kondisional untuk berjaga-jaga jika produk tidak punya kategori.
        $relatedProducts = $product->category_id
            ? Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->inRandomOrder()
            ->take(4)
            ->get()
            : collect(); // Kembalikan koleksi kosong jika tidak ada kategori

        return view('products.show', compact('product', 'relatedProducts'));
    }
}
