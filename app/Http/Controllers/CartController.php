<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // Menampilkan halaman keranjang
    public function index()
    {
        $cart = Cart::with('items.product')->firstOrCreate(['user_id' => Auth::id()]);
        return view('cart', compact('cart'));
    }

    // Tambah produk ke keranjang
    public function add(Request $request)
    {
        // Validasi input JSON
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        // Cek atau buat cart untuk user yang sedang login
        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);

        // Cek apakah produk ini sudah ada di keranjang user
        $cartItem = CartItem::where('cart_id', $cart->id)
                            ->where('product_id', $request->product_id)
                            ->first();

        if ($cartItem) {
            // Jika sudah ada, cukup tambahkan quantity-nya (+1 atau lebih)
            $cartItem->quantity += $request->quantity;
            $cartItem->save();
        } else {
            // Jika belum ada, buat record item baru
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity
            ]);
        }

        // Hitung total jenis barang unik di keranjang
        $totalItems = CartItem::where('cart_id', $cart->id)->count();

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil ditambahkan!',
            'total_items' => $totalItems
        ]);
    }


    // API: Update Qty (Fetch API)
    public function updateQuantity(Request $request, CartItem $cartItem)
    {
        // Validasi kepemilikan keranjang
        if ($cartItem->cart->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate(['action' => 'required|in:increase,decrease']);

        if ($request->action === 'increase') {
            $cartItem->increment('quantity');
        } else {
            if ($cartItem->quantity > 1) {
                $cartItem->decrement('quantity');
            }
        }

        return $this->getCartSummary($cartItem->cart_id);
    }

    // API: Hapus Item (Fetch API)
    public function removeItem(CartItem $cartItem)
    {
        if ($cartItem->cart->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $cartId = $cartItem->cart_id;
        $cartItem->delete();

        return $this->getCartSummary($cartId);
    }

    // Helper untuk menghitung ulang total dan mengembalikan JSON
    private function getCartSummary($cartId)
    {
        $cart = Cart::with('items.product')->find($cartId);
        $total = $cart->items->sum(function ($item) {
            return $item->quantity * $item->product->base_price;
        });

        return response()->json([
            'success' => true,
            'subtotal' => $total, // Di tahap ini subtotal dan total sama (belum ada diskon)
            'total' => $total,
            'item_count' => $cart->items->count()
        ]);
    }

    // Memproses Checkout
    public function checkout(Request $request)
    {
        $user = Auth::user();
        $cart = Cart::with('items.product')->where('user_id', $user->id)->first();

        if (!$cart || $cart->items->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Keranjang kosong'], 400);
        }

        DB::beginTransaction();
        try {
            // 1. Hitung Grand Total
            $grandTotal = $cart->items->sum(function ($item) {
                return $item->quantity * $item->product->base_price;
            });

            // 2. Buat Header Order
            $order = Order::create([
                // Generate Invoice Number otomatis dari Boot method Model (Fase 3)
                'user_id' => $user->id,
                'shipping_address' => $user->address, // Snapshot alamat
                'order_source' => 'online',
                'status' => 'pending_approval',
                'grand_total' => $grandTotal,
            ]);

            // 3. Pindahkan Cart Item ke Order Item (Snapshot Harga)
            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'cost_price_at_time' => $item->product->cost_price,
                    'price_at_time' => $item->product->base_price,
                    'subtotal' => $item->quantity * $item->product->base_price,
                ]);
            }

            // 4. Kosongkan Keranjang
            $cart->items()->delete();

            DB::commit();

            // Kembalikan URL redirect (misal ke halaman sukses atau detail pesanan)
            return response()->json([
                'success' => true,
                'redirect_url' => url('/') // Ganti dengan route pesanan sukses nanti
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan sistem.'], 500);
        }
    }
}
