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

    public function checkout(Request $request)
    {
        $user = Auth::user();
        $cart = Cart::with('items.product')->where('user_id', $user->id)->first();

        if (!$cart || $cart->items->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Keranjang kosong'], 400);
        }

        DB::beginTransaction();
        try {
            $grandTotal = $cart->items->sum(function ($item) {
                return $item->quantity * $item->product->base_price;
            });

            $order = Order::create([
                'user_id' => $user->id,
                'shipping_address' => $user->address,
                'order_source' => 'online',
                'status' => 'pending_approval',
                'grand_total' => $grandTotal,
            ]);

            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'cost_price_at_time' => $item->product->cost_price ?? 0,
                    'price_at_time' => $item->product->base_price,
                    'subtotal' => $item->quantity * $item->product->base_price,
                ]);
            }

            $cart->items()->delete();
            DB::commit();

            return response()->json([
                'success' => true,
                'redirect_url' => url('/') // Nanti arahkan ke halaman sukses
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan sistem.'], 500);
        }
    }
}
