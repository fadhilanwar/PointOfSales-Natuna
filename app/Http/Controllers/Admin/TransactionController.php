<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderPayment;
use App\Models\Courier;
use App\Models\StockMutation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'payments'])->latest();

        if ($request->filled('delivery_status')) $query->where('delivery_status', $request->delivery_status);
        if ($request->filled('payment_status')) $query->where('payment_status', $request->payment_status);

        $transactions = $query->paginate(10);
        return view('admin.pages.transactions.index', compact('transactions'));
    }

    public function show($id)
    {
        $transaction = Order::with(['user', 'items.product', 'payments' => function($q) {
            $q->latest();
        }, 'courier'])->findOrFail($id);
        
        $couriers = Courier::all();
        return view('admin.pages.transactions.show', compact('transaction', 'couriers'));
    }

    // --- FUNGSI 1: MENGATUR PENGIRIMAN ---
    public function updateDelivery(Request $request, $id)
    {
        $transaction = Order::findOrFail($id);
        try {
            DB::beginTransaction();
            $newDelivery = $request->delivery_status;

            // Jika diproses kirim, kurir wajib diisi
            if ($newDelivery == 'shipping') {
                $request->validate(['courier_id' => 'required|exists:couriers,id']);
                $transaction->update(['courier_id' => $request->courier_id]);
            }

            // Batalkan dan Restock
            if ($newDelivery == 'cancelled' && $transaction->delivery_status != 'cancelled') {
                foreach ($transaction->items as $item) {
                    if ($item->product) {
                        $item->product->increment('stock', $item->quantity);
                        StockMutation::create([
                            'product_id' => $item->product_id,
                            'order_id' => $transaction->id,
                            'type' => 'in',
                            'quantity' => $item->quantity,
                            'description' => 'Pesanan Dibatalkan: ' . $transaction->invoice_number,
                        ]);
                    }
                }
            }

            $transaction->update(['delivery_status' => $newDelivery]);
            DB::commit();
            return back()->with('success', 'Status Logistik / Pengiriman Diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    // --- FUNGSI 2: VERIFIKASI BUKTI TRANSFER (ACC/TOLAK) ---
    public function verifyPayment(Request $request, $order_id, $payment_id)
    {
        $order = Order::findOrFail($order_id);
        $payment = OrderPayment::findOrFail($payment_id);

        try {
            DB::beginTransaction();

            if ($request->action == 'reject') {
                $payment->update(['status' => 'rejected']);
                DB::commit();
                return back()->with('error', 'Bukti pembayaran ditolak. Menunggu user mengunggah ulang.');
            }

            if ($request->action == 'approve') {
                $request->validate(['amount' => 'required|numeric|min:1']);
                $inputAmount = $request->amount;

                // Tentukan Tipe: Full atau Installment
                $type = ($inputAmount < $order->grand_total) ? 'installment' : 'full_payment';

                $payment->update([
                    'status' => 'approved',
                    'amount' => $inputAmount,
                    'type'   => $type
                ]);

                $this->checkIfLunas($order);

                DB::commit();
                return back()->with('success', 'Pembayaran di-ACC! Nominal Rp ' . number_format($inputAmount,0,',','.'));
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // --- FUNGSI 3: INPUT MANUAL (COD YANG BARU BAYAR SEPARUH / SUSULAN) ---
    public function addManualPayment(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $request->validate(['amount' => 'required|numeric|min:1']);
        
        $inputAmount = $request->amount;
        $type = ($inputAmount < $order->grand_total) ? 'installment' : 'full_payment';

        try {
            DB::beginTransaction();
            OrderPayment::create([
                'order_id'       => $order->id,
                'amount'         => $inputAmount,
                'type'           => $type,
                'payment_method' => 'cod', // Dianggap COD/Tunai
                'status'         => 'approved'
            ]);

            $this->checkIfLunas($order);

            DB::commit();
            return back()->with('success', 'Angsuran Tunai/COD berhasil dicatat!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Helper untuk mengecek apakah total sudah menyentuh Grand Total
    private function checkIfLunas($order)
    {
        if ($order->total_paid >= $order->grand_total) {
            $order->update(['payment_status' => 'lunas']);
        } else {
            $order->update(['payment_status' => 'belum_lunas']);
        }
    }
}