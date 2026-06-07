<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Purchase;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    // --- 1. Laporan Transaksi (Penjualan) ---
    public function transactionReport(Request $request)
    {
        $query = Order::with('user');
        
        if ($request->filled('month')) {
            $query->whereMonth('created_at', $request->month);
        }
        if ($request->filled('year')) {
            $query->whereYear('created_at', $request->year);
        }

        $transactions = $query->latest()->get();

        // Hitung Ringkasan (Hanya yang statusnya bukan dibatalkan)
        $totalRevenue = $transactions->where('delivery_status', '!=', 'cancelled')->sum('grand_total');
        $totalOrders = $transactions->count();

        return view('admin.pages.reports.transaction', compact('transactions', 'totalRevenue', 'totalOrders'));
    }

    // --- 2. Laporan Suplai (Pembelian Barang) ---
    public function supplyReport(Request $request)
    {
        $query = Purchase::with('supplier');

        if ($request->filled('month')) {
            $query->whereMonth('created_at', $request->month);
        }
        if ($request->filled('year')) {
            $query->whereYear('created_at', $request->year);
        }

        $supplies = $query->latest()->get();

        // Hitung Ringkasan (Hanya yang statusnya bukan dibatalkan)
        $totalSpending = $supplies->where('status', '!=', 'cancelled')->sum('grand_total');
        $totalSupplies = $supplies->count();

        return view('admin.pages.reports.supply', compact('supplies', 'totalSpending', 'totalSupplies'));
    }

    // --- 3. Detail Suplai Barang ---
    public function showSupply($id)
    {
        // Load relasi supplier dan detail barang yang dibeli (items.product)
        $supply = Purchase::with(['supplier', 'items.product'])->findOrFail($id);
        
        return view('admin.pages.reports.supply_show', compact('supply'));
    }

    // ... fungsi transactionReport sebelumnya ...

    // FUNGSI BARU: Detail Rekap Penjualan (Read-Only)
    public function showTransaction($id)
    {
        $transaction = Order::with(['user', 'items.product', 'payments' => function($q) {
            $q->latest();
        }, 'courier'])->findOrFail($id);
        
        return view('admin.pages.reports.transaction_show', compact('transaction'));
    }
}