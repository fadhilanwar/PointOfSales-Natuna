<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        // Ambil 8 produk terbaru
        $products = Product::latest()->take(8)->get();
        // ambil 5 kategori produk
        $categories = Category::take(5)->get();

        // Cek pesanan aktif jika user sudah login (Untuk Widget Status Pengiriman)
        $activeOrder = null;
        if (Auth::check()) {
            $activeOrder = Order::where('user_id', Auth::id())
                ->whereIn('status', ['approved', 'shipping'])
                ->with('courier') // Eager load relasi kurir
                ->latest()
                ->first();
        }

        return view('home', compact('products', 'activeOrder', 'categories'));
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

    /**
     * Memproses pencarian produk
     */
    public function search(Request $request)
    {
        // 1. Tangkap kata kunci dari input form (name="q")
        $query = $request->input('q');

        // 2. Jika user menekan enter tanpa mengetik apa-apa, kembalikan ke Beranda
        if (empty($query)) {
            return redirect()->route('home');
        }

        // 3. Cari produk berdasarkan nama atau deskripsi, lalu paginasi 12 per halaman
        $products = Product::where('name', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->latest()
            ->paginate(12)
            ->appends(['q' => $query]); // Mempertahankan parameter 'q' saat pindah halaman

        // 4. Lempar data ke view pencarian
        return view('products.search', compact('products', 'query'));
    }
}
