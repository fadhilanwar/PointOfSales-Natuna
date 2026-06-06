<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\StockMutation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function index()
    {
        $purchases = Purchase::with(['supplier'])->latest()->get();
        return view('admin.pages.purchase.index', compact('purchases'));
    }

    public function create()
    {
        $suppliers = Supplier::all();
        $products = Product::all();
        // Generate Nomor Invoice otomatis (Contoh: PO-20260606-1234)
        $invoice_number = 'PO-' . date('Ymd') . '-' . rand(1000, 9999);
        
        return view('admin.pages.purchase.create', compact('suppliers', 'products', 'invoice_number'));
    }


    public function show(Purchase $purchase)
    {
        // Panggil data purchase beserta supplier dan item-itemnya (dan produk tiap item)
        $purchase->load(['supplier', 'items.product']);
        
        return view('admin.pages.purchase.show', compact('purchase'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'invoice_number' => 'required|unique:purchases,invoice_number',
            'supplier_id' => 'required|exists:suppliers,id',
            'product_id' => 'required|array',
            'product_id.*' => 'required|exists:products,id',
            'quantity' => 'required|array',
            'cost_price' => 'required|array',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // 1. Hitung Grand Total dari array inputan
            $grand_total = 0;
            $items = [];
            
            for ($i = 0; $i < count($request->product_id); $i++) {
                $qty = $request->quantity[$i];
                $price = $request->cost_price[$i];
                $subtotal = $qty * $price;
                $grand_total += $subtotal;

                $items[] = [
                    'product_id' => $request->product_id[$i],
                    'quantity' => $qty,
                    'cost_price' => $price,
                    'subtotal' => $subtotal,
                ];
            }

            // 2. Simpan Data Utama (Purchase)
            $purchase = Purchase::create([
                'invoice_number' => $request->invoice_number,
                'supplier_id' => $request->supplier_id,
                'status' => 'pending', // Status awal masuk selalu pending
                'grand_total' => $grand_total,
                'notes' => $request->notes,
            ]);

            // 3. Simpan Detail Barang (Purchase Items)
            foreach ($items as $item) {
                $purchase->items()->create($item);
            }

            DB::commit();
            return redirect()->route('admin.purchases.index')->with('success', 'Transaksi Pembelian berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    // ... method sebelumnya (index, create, store) ...

    public function receiveItem(Purchase $purchase)
    {
        // Cegah jika status sudah bukan pending
        if ($purchase->status !== 'pending') {
            return back()->with('error', 'Barang ini sudah diproses sebelumnya!');
        }

        try {
            DB::beginTransaction();

            // 1. Ubah status menjadi Diterima (received)
            $purchase->update(['status' => 'received']);

            // 2. Looping semua item yang ada di invoice ini
            foreach ($purchase->items as $item) {
                // Cari produk aslinya
                $product = Product::find($item->product_id);
                
                if ($product) {
                    // A. Tambah stok produk
                    $product->increment('stock', $item->quantity);

                    // B. Catat sejarahnya di Mutasi Stok
                    StockMutation::create([
                        'product_id' => $product->id,
                        'type' => 'in',
                        'quantity' => $item->quantity,
                        'description' => 'Restock dari PO: ' . $purchase->invoice_number,
                    ]);
                }
            }

            DB::commit();
            return back()->with('success', 'Barang berhasil diterima! Stok telah otomatis ditambahkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses penerimaan barang: ' . $e->getMessage());
        }
    }
}