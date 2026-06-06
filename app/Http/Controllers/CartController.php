<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index()
    {
        $cart = Cart::with('items.product')->firstOrCreate(['user_id' => Auth::id()]);
        return view('cart', compact('cart'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $request->product_id)
            ->first();

        if ($cartItem) {
            $cartItem->quantity += $request->quantity;
            $cartItem->save();
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil ditambahkan!',
            'total_items' => CartItem::where('cart_id', $cart->id)->count()
        ]);
    }

    public function updateQuantity(Request $request, CartItem $cartItem)
    {
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

    private function getCartSummary($cartId)
    {
        $cart = Cart::with('items.product')->find($cartId);
        $total = $cart->items->sum(function ($item) {
            return $item->quantity * $item->product->base_price;
        });

        return response()->json([
            'success' => true,
            'subtotal' => $total,
            'total' => $total,
            'item_count' => $cart->items->count()
        ]);
    }

    public function directCheckout(Request $request)
    {
        // 1. Validasi input standar
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        DB::beginTransaction();
        try {
            // 2. Ambil atau buat keranjang user saat ini
            $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);

            // 3. Kosongkan keranjang lama agar tidak bercampur
            $cart->items()->delete();

            // 4. Masukkan barang yang mau dibeli langsung ini ke keranjang
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity
            ]);

            DB::commit();

            // 5. KEMBALIKAN REDIRECT URL KE HALAMAN CHECKOUT (Tidak usah buat Order di sini!)
            return response()->json([
                'success' => true,
                'redirect_url' => route('checkout.index') // <--- Arahkan ke halaman pemilihan pembayaran
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            // Tampilkan pesan error asli di console/network untuk mempermudah debugging jika masih gagal
            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses pembelian: ' . $e->getMessage()
            ], 500);
        }
    }
}
