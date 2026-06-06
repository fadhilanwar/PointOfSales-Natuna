<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CheckoutController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $cart = Cart::with('items.product')->where('user_id', $user->id)->first();

        // Cek jika keranjang kosong atau tidak ada, tendang kembali ke halaman keranjang
        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja Anda masih kosong.');
        }

        // Tampilkan halaman checkout dan bawa data keranjangnya
        return view('checkout', compact('cart'));
    }

    public function process(Request $request)
    {
        // 1. Validasi HANYA untuk Alamat dan Metode Pembayaran
        $request->validate([
            'shipping_address' => 'required|string',
            'payment_method' => 'required|in:transfer,cod,hutang',
        ]);

        $user = Auth::user();
        $cart = Cart::with('items.product')->where('user_id', $user->id)->first();

        if (!$cart || $cart->items->isEmpty()) {
            return back()->with('error', 'Keranjang belanja Anda masih kosong.');
        }

        $grandTotal = $cart->items->sum(function ($item) {
            return $item->quantity * $item->product->base_price;
        });

        // 2. Set Status Berdasarkan Metode Pembayaran
        $initialStatus = ($request->payment_method === 'transfer') ? 'awaiting_payment' : 'pending_approval';

        DB::beginTransaction();
        try {
            $datePrefix = now()->format('Ymd');
            $lastOrder = Order::where('invoice_number', 'LIKE', "INV-{$datePrefix}-%")
                ->lockForUpdate()
                ->latest('id')
                ->first();

            $sequence = $lastOrder ? ((int) substr($lastOrder->invoice_number, -4)) + 1 : 1;
            $invoiceNumber = sprintf('INV-%s-%04d', $datePrefix, $sequence);

            $order = Order::create([
                'invoice_number' => $invoiceNumber,
                'user_id' => $user->id,
                'shipping_address' => $request->shipping_address,
                'order_source' => 'online',
                'status' => $initialStatus, // Status dinamis
                'payment_method' => $request->payment_method,
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

            // 3. Logika Redirect Kondisional
            if ($request->payment_method === 'transfer') {
                return redirect()->route('checkout.payment', $order)
                    ->with('success', 'Pesanan dibuat! Silakan unggah bukti transfer Anda.');
            }

            return redirect()->route('orders.show', $order)
                ->with('success', 'Pesanan berhasil dibuat dan menunggu konfirmasi Admin.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    // FUNGSI: Menampilkan Halaman Upload Bukti
    public function showPaymentPage(Order $order)
    {
        // Proteksi: Hanya pemilik pesanan dan status harus awaiting_payment
        if ($order->user_id !== Auth::id() || $order->status !== 'awaiting_payment') {
            return redirect()->route('orders.index')->with('error', 'Akses ditolak atau pesanan tidak memerlukan pembayaran saat ini.');
        }

        // Ambil data rekening bank yang sedang aktif
        $banks = BankAccount::where('is_active', true)->get();

        return view('payment', compact('order', 'banks'));
    }

    // FUNGSI: Memproses Upload File
    public function uploadPaymentProof(Request $request, Order $order)
    {
        if ($order->user_id !== Auth::id() || $order->status !== 'awaiting_payment') {
            abort(403);
        }

        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ], [
            'payment_proof.required' => 'Mohon pilih file gambar bukti transfer terlebih dahulu.',
            'payment_proof.image' => 'File harus berupa gambar.',
            'payment_proof.max' => 'Ukuran gambar maksimal 5MB.'
        ]);

        $path = $request->file('payment_proof')->store('payment_proofs', 'public');

        $order->update([
            'payment_proof_path' => $path,
            'status' => 'pending_approval' // Ubah status setelah upload sukses
        ]);

        // PERBAIKAN: Redirect ke orders.show dengan membawa ID order
        return redirect()->route('orders.show', $order->invoice_number)
            ->with('success', 'Bukti pembayaran berhasil diunggah. Menunggu konfirmasi Admin.');
    }
}
