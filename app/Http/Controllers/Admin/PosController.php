<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\Product;
use App\Models\StockMutation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{
    public function index()
    {
        $products = Product::where('stock', '>', 0)->latest()->get();
        $customers = User::where('role', 'user')->latest()->get();
        $invoice_number = 'INV-'.date('Ymd').'-'.rand(1000, 9999);

        // 1. Ambil Keranjang dari Database langsung ke View
        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
        $cartItems = $cart->items()->with('product')->get();

        // 2. Hitung Grand Total di Controller
        $grandTotal = 0;
        
        foreach ($cartItems as $item) {
            // Gunakan Null Coalescing Operator (??) untuk memilih harga
            $priceToUse = $item->custom_price ?? $item->product?->base_price ?? 0;
            $grandTotal += ($item->quantity * $priceToUse);
        }

        return view('admin.pages.pos.index', compact('products', 'customers', 'invoice_number', 'cartItems', 'grandTotal'));
    }

    public function addToCart(Request $request)
    {
        $request->validate(['product_id' => 'required|exists:products,id']);

        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
        $product = Product::findOrFail($request->product_id);
        $cartItem = $cart->items()->where('product_id', $product->id)->first();

        if ($cartItem) {
            if ($cartItem->quantity + 1 > $product->stock) {
                return back()->with('error', 'Stok produk tidak mencukupi!');
            }
            $cartItem->increment('quantity');
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => 1,
            ]);
        }

        return back(); // Refresh halaman ala Laravel
    }

    public function updateCart(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'cart_item_id' => 'required|exists:cart_items,id',
            'action'       => 'nullable|string',
            'quantity'     => 'nullable|integer|min:0',
            'custom_price' => 'nullable|numeric|min:0',
        ]);

        // 2. Cari data item di keranjang beserta data produknya
        $cartItem = \App\Models\CartItem::with('product')->find($request->cart_item_id);

        if (!$cartItem) {
            return back()->with('error', 'Item tidak ditemukan di keranjang.');
        }

        // 3. FUNGSI BARU: Tangkap Request Edit Harga Khusus (Nego)
        if ($request->action === 'update_price') {
            $cartItem->update([
                'custom_price' => $request->custom_price
            ]);
            return back()->with('success', 'Harga khusus / harga nego berhasil diterapkan!');
        }

        // 4. Logika Update Kuantitas (+ / - / Input Manual)
        $newQuantity = $cartItem->quantity;

        if ($request->action === 'plus') {
            $newQuantity++;
        } elseif ($request->action === 'minus') {
            $newQuantity--;
        } elseif ($request->has('quantity')) {
            $newQuantity = $request->quantity;
        }

        // 5. Cek Jika Kuantitas 0 (Hapus dari Keranjang)
        if ($newQuantity <= 0) {
            $cartItem->delete();
            return back()->with('success', 'Barang dihapus dari keranjang belanja.');
        }

        // 6. Validasi Sisa Stok Master Produk
        if ($cartItem->product && $newQuantity > $cartItem->product->stock) {
            return back()->with('error', 'Gagal! Sisa stok ' . $cartItem->product->name . ' hanya ' . $cartItem->product->stock);
        }

        // 7. Simpan Update Kuantitas
        $cartItem->update([
            'quantity' => $newQuantity
        ]);

        return back();
    }

    // --- PROSES PEMBAYARAN KASIR ---

    public function store(Request $request)
    {
        $cart = Cart::where('user_id', Auth::id())->first();

        if (! $cart || $cart->items->count() == 0) {
            return back()->with('error', 'Keranjang belanja kosong!');
        }

        // Pastiin ada nama pembeli kalau Ngutang
        if ($request->payment_method == 'hutang' && empty($request->user_id)) {
            return back()->with('error', 'Gagal! Transaksi Hutang wajib mengisi Nama Pembeli.');
        }

        $request->validate([
            'invoice_number' => 'required|unique:orders,invoice_number',
            'user_id' => 'required_if:payment_method,hutang|nullable|exists:users,id',
            'payment_method' => 'required|in:cod,transfer,hutang',
            'amount_paid' => 'required|numeric|min:0',
            'grand_total' => 'required|numeric|min:0',
        ]);

        $change_amount = $request->amount_paid - $request->grand_total;

        if ($change_amount < 0 && $request->payment_method != 'hutang') {
            return back()->with('error', 'Uang pembayaran kurang dari Total Belanja!');
        }

        try {
            DB::beginTransaction();

            if ($request->amount_paid < $request->grand_total) {

                $order = Order::create([
                    'invoice_number' => $request->invoice_number,
                    'user_id' => $request->user_id,
                    'delivery_status' => 'delivered',
                    'payment_status' => 'belum_lunas',
                    'payment_method' => $request->payment_method,
                    'grand_total' => $request->grand_total,
                ]);

            } elseif ($request->amount_paid == $request->grand_total) {
                $order = Order::create([
                    'invoice_number' => $request->invoice_number,
                    'user_id' => $request->user_id,
                    'delivery_status' => 'delivered',
                    'payment_status' => 'lunas',
                    'payment_method' => $request->payment_method,
                    'grand_total' => $request->grand_total,
                ]);
            }

            foreach ($cart->items as $item) {
                $order->items()->create([
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'cost_price_at_time' => $item->product->cost_price,
                    'price_at_time' => $item->product->base_price,
                    'subtotal' => $item->quantity * $item->product->base_price,
                ]);

                $item->product->decrement('stock', $item->quantity);

                StockMutation::create([
                    'product_id' => $item->product_id,
                    'order_id' => $order->id,
                    'type' => 'out',
                    'quantity' => $item->quantity,
                    'description' => 'Terjual via Kasir: '.$order->invoice_number,
                ]);
            }

            $cart->items()->delete();

            DB::commit();

            return redirect()->route('admin.pos.index')->with([
                'success' => 'Transaksi berhasil dibuat!',
                'order_id' => $order->id, // Sesuaikan dengan variabel pesanan Anda
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Terjadi kesalahan sistem: '.$e->getMessage());
        }

        // ... kode simpan transaksi pos ...

    }

    public function receipt($id)
    {
        // Ambil data order khusus untuk struk
        $order = Order::with(['user', 'items.product', 'payments'])->findOrFail($id);

        return view('admin.pages.pos.receipt', compact('order'));
    }
}
