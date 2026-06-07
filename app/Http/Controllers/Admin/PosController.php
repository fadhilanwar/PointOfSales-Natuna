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
            $grandTotal += ($item->quantity * $item->product->base_price);
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
        $request->validate([
            'cart_item_id' => 'required|exists:cart_items,id',
        ]);

        $cartItem = CartItem::findOrFail($request->cart_item_id);

        // 1. Jika request berasal dari tombol Plus / Minus
        if ($request->has('action')) {
            if ($request->action == 'plus') {
                if ($cartItem->quantity + 1 > $cartItem->product->stock) {
                    return back()->with('error', 'Stok maksimal tercapai!');
                }
                $cartItem->increment('quantity');
            } elseif ($request->action == 'minus') {
                if ($cartItem->quantity - 1 <= 0) {
                    $cartItem->delete();
                } else {
                    $cartItem->decrement('quantity');
                }
            }
        }
        // 2. Jika request berasal dari Textfield (Ketik Angka + Enter)
        elseif ($request->has('quantity')) {
            $qty = (int) $request->quantity;
            if ($qty <= 0) {
                $cartItem->delete();
            } elseif ($qty > $cartItem->product->stock) {
                $cartItem->update(['quantity' => $cartItem->product->stock]);

                return back()->with('error', 'Stok maksimal untuk '.$cartItem->product->name.' hanya '.$cartItem->product->stock);
            } else {
                $cartItem->update(['quantity' => $qty]);
            }
        }

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
