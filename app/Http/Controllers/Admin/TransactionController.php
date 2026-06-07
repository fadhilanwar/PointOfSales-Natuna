<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\StockMutation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        // Mengambil semua data order beserta relasi user
        $query = Order::with(['user'])->latest();

        // Filter Status Pengiriman
        if ($request->filled('delivery_status')) {
            $query->where('delivery_status', $request->delivery_status);
        }

        // Filter Status Pembayaran
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        $transactions = $query->paginate(10);
        return view('admin.pages.transactions.index', compact('transactions'));
    }

    public function show($id)
    {
        // Mengambil detail transaksi, user, dan item produk
        $transaction = Order::with(['user', 'items.product'])->findOrFail($id);
        return view('admin.pages.transactions.show', compact('transaction'));
    }

    public function updateStatus(Request $request, $id)
    {
        $transaction = Order::findOrFail($id);

        try {
            DB::beginTransaction();

            // 1. Update Status Pengiriman
            if ($request->has('delivery_status')) {
                $newDelivery = $request->delivery_status;
                $validDelivery = ['pending', 'processing', 'shipping', 'delivered', 'cancelled'];
                
                if (in_array($newDelivery, $validDelivery)) {
                    // Jika dibatalkan, kembalikan stok
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
                }
            }

            // 2. Update Status Pembayaran
            if ($request->has('payment_status')) {
                $newPayment = $request->payment_status;
                $validPayment = ['belum_lunas', 'lunas'];
                
                if (in_array($newPayment, $validPayment)) {
                    $transaction->update(['payment_status' => $newPayment]);
                }
            }
            
            DB::commit();
            return back()->with('success', 'Status pesanan berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }
}