<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    // ============================================================
    // HALAMAN CHECKOUT: Menampilkan HANYA item yang dicentang
    // ============================================================
    public function index(Request $request)
    {
        $user = Auth::user();

        // 1. Tangkap array ID keranjang yang dicentang oleh user (name="selected_cart_ids[]")
        $selectedIds = $request->input('selected_cart_ids');

        // Jika user memaksa masuk ke URL checkout tanpa milih barang, tendang balik
        if (!$selectedIds || empty($selectedIds)) {
            return redirect()->route('cart.index')->with('error', 'Silakan pilih minimal satu barang untuk di-checkout.');
        }

        // 2. Ambil data keranjang milik user, TAPI sertakan kondisi filter item yang ID-nya ada di array $selectedIds
        $cart = Cart::with(['items' => function ($query) use ($selectedIds) {
            // Filter query item: hanya ambil yang id-nya dicentang
            $query->whereIn('id', $selectedIds)->with('product');
        }])->where('user_id', $user->id)->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Barang tidak ditemukan di keranjang Anda.');
        }

        // Lempar variabel array ini ke view agar saat user tekan "Buat Pesanan", kita bisa mengirim ID ini lagi
        return view('checkout', compact('cart', 'selectedIds'));
    }

    // ============================================================
    // PROSES CHECKOUT: Menyimpan Pesanan (Hanya barang yang dicentang)
    // ============================================================
    public function process(Request $request)
    {
        // 1. Validasi
        $request->validate([
            'shipping_address'    => 'required|string',
            'payment_method'      => 'required|in:transfer,cod',
            'selected_cart_ids'   => 'required|array', // Wajib menerima array ID
            'selected_cart_ids.*' => 'exists:cart_items,id' // Pastikan ID-nya benar-benar ada di database
        ]);

        $user = Auth::user();

        // 2. Ambil barang yang HANYA dicentang (sama seperti fungsi index)
        $cart = Cart::with(['items' => function ($query) use ($request) {
            $query->whereIn('id', $request->selected_cart_ids)->with('product');
        }])->where('user_id', $user->id)->first();

        if (!$cart || $cart->items->isEmpty()) {
            return back()->with('error', 'Barang yang dipilih tidak valid.');
        }

        // 3. Hitung Grand Total dari item yang difilter
        $grandTotal = 0;
        foreach ($cart->items as $item) {
            $grandTotal += $item->quantity * $item->product->base_price;
        }

        DB::beginTransaction();
        try {
            $datePrefix = now()->format('Ymd');
            $lastOrder  = Order::where('invoice_number', 'LIKE', "INV-{$datePrefix}-%")
                ->lockForUpdate()
                ->latest('id')
                ->first();

            $sequence      = $lastOrder ? ((int) substr($lastOrder->invoice_number, -4)) + 1 : 1;
            $invoiceNumber = sprintf('INV-%s-%04d', $datePrefix, $sequence);

            $order = Order::create([
                'user_id'          => $user->id,
                'invoice_number'   => $invoiceNumber,
                'shipping_address' => $request->shipping_address,
                'grand_total'      => $grandTotal,
                'delivery_status'  => 'pending',
                'payment_status'   => 'belum_lunas',
            ]);

            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id'           => $order->id,
                    'product_id'         => $item->product_id,
                    'quantity'           => $item->quantity,
                    'cost_price_at_time' => $item->product->cost_price ?? 0,
                    'price_at_time'      => $item->product->base_price,
                    'subtotal'           => $item->quantity * $item->product->base_price,
                ]);
            }

            OrderPayment::create([
                'order_id'           => $order->id,
                'amount'             => $grandTotal,
                'type'               => 'full_payment',
                'payment_method'     => $request->payment_method,
                'payment_proof_path' => null,
                'status'             => 'pending',
            ]);

            // 4. Hapus barang HANYA yang dicentang (Barang lain yang tidak dicentang tetap aman di keranjang)
            $cart->items()->whereIn('id', $request->selected_cart_ids)->delete();

            DB::commit();

            if ($request->payment_method === 'transfer') {
                return redirect()->route('checkout.payment', $order->invoice_number)
                    ->with('success', 'Pesanan dibuat! Silakan unggah bukti transfer Anda.');
            }

            return redirect()->route('orders.show', $order->invoice_number)
                ->with('success', 'Pesanan berhasil dibuat! Admin akan segera memproses pesanan Anda.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    // ============================================================
    // HALAMAN UPLOAD BUKTI: Untuk payment transfer (checkout awal)
    // ============================================================
    public function showPaymentPage(Order $order)
    {
        // Proteksi: hanya pemilik order yang boleh akses
        if ($order->user_id !== Auth::id()) {
            return redirect()->route('orders.index')->with('error', 'Akses ditolak.');
        }

        // Cek apakah order ini memang metode transfer dan masih ada payment yang pending
        $pendingPayment = $order->payments()
            ->where('payment_method', 'transfer')
            ->where('status', 'pending')
            ->whereNull('payment_proof_path') // Belum diupload sama sekali
            ->latest()
            ->first();

        if (!$pendingPayment) {
            return redirect()->route('orders.show', $order->invoice_number)
                ->with('error', 'Tidak ada pembayaran yang perlu dikonfirmasi saat ini.');
        }

        // Ambil daftar rekening bank yang aktif
        $banks = BankAccount::where('is_active', true)->get();

        return view('payment', compact('order', 'banks', 'pendingPayment'));
    }

    // ============================================================
    // PROSES UPLOAD BUKTI: Update payment_proof_path di order_payments
    // ============================================================
    public function uploadPaymentProof(Request $request, Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ], [
            'payment_proof.required' => 'Mohon pilih file gambar bukti transfer.',
            'payment_proof.image'    => 'File harus berupa gambar.',
            'payment_proof.max'      => 'Ukuran gambar maksimal 5MB.',
        ]);

        // Simpan file ke storage/app/public/payment_proofs/
        $path = $request->file('payment_proof')->store('payment_proofs', 'public');

        // Update entri payment yang belum ada buktinya (masih pending, belum diupload)
        $payment = $order->payments()
            ->where('status', 'pending')
            ->whereNull('payment_proof_path')
            ->latest()
            ->first();

        if (!$payment) {
            return back()->with('error', 'Tidak ada pembayaran yang perlu dikonfirmasi.');
        }

        // Simpan path foto bukti transfer — status tetap 'pending', admin yang approve
        $payment->update([
            'payment_proof_path' => $path,
        ]);

        return redirect()->route('orders.show', $order->invoice_number)
            ->with('success', 'Bukti transfer berhasil dikirim. Menunggu konfirmasi Admin.');
    }

    // ============================================================
    // FUNGSI PELUNASAN: User upload bukti bayar tambahan
    // Dipanggil dari halaman detail pesanan (orders.show)
    // ============================================================
    public function storeAdditionalPayment(Request $request, Order $order)
    {
        // Proteksi: hanya pemilik order
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        // Kalau order sudah lunas, tidak perlu bayar lagi
        if ($order->payment_status === 'lunas') {
            return back()->with('error', 'Pesanan ini sudah lunas.');
        }

        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ], [
            'payment_proof.required' => 'Mohon pilih file gambar bukti transfer.',
            'payment_proof.image'    => 'File harus berupa gambar.',
            'payment_proof.max'      => 'Ukuran gambar maksimal 5MB.',
        ]);

        // Hitung sisa tagihan yang belum dibayar
        // total_paid adalah accessor dari Model Order: jumlah payment yang status = 'approved'
        $sudahDibayar = $order->total_paid;
        $sisaTagihan  = $order->grand_total - $sudahDibayar;

        if ($sisaTagihan <= 0) {
            return back()->with('error', 'Tidak ada sisa tagihan yang perlu dibayar.');
        }

        // Simpan bukti bayar
        $path = $request->file('payment_proof')->store('payment_proofs', 'public');

        // Tambah BARIS BARU di order_payments (jangan edit yang lama!)
        // Ini yang menjaga riwayat cicilan tetap tercatat semua
        OrderPayment::create([
            'order_id'           => $order->id,
            'amount'             => $sisaTagihan, // Asumsikan user bayar sisa semuanya
            'type'               => 'installment', // Ini cicilan/pelunasan, bukan pembayaran awal
            'payment_method'     => 'transfer',
            'payment_proof_path' => $path,
            'status'             => 'pending', // Admin tetap harus verifikasi dulu
        ]);

        return redirect()->route('orders.show', $order->invoice_number)
            ->with('success', 'Bukti pelunasan berhasil dikirim. Menunggu konfirmasi Admin.');
    }
}
