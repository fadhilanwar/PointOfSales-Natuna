<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('payments')->where('user_id', Auth::id())->latest();

        if ($request->filled('search')) {
            $query->where('invoice_number', 'like', '%' . $request->search . '%');
        }

        $activeTab = $request->get('tab', 'semua');

        if ($activeTab === 'diproses') {
            $query->where('delivery_status', 'processing');
        } elseif ($activeTab === 'dikirim') {
            $query->where('delivery_status', 'shipping');
        } elseif ($activeTab === 'belum_lunas') {
            $query->where('payment_status', 'belum_lunas');
        } elseif ($activeTab === 'selesai') {
            $query->where('delivery_status', 'delivered');
        }

        $orders = $query->get();

        return view('orders.index', compact('orders', 'activeTab'));
    }

    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Akses Ditolak.');
        }

        // Load relasi items, produknya, dan semua riwayat pembayaran
        $order->load(['items.product', 'payments']);

        return view('orders.show', compact('order'));
    }
}
