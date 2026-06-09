<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\StockMutation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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
            'supplier_id'    => 'required', 
            'product_id'     => 'required|array',
            'product_id.*'   => 'required',
            'quantity'       => 'required|array',
            'cost_price'     => 'required|array',
            'notes'          => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $supplierInput = $request->supplier_id;
            $supplier = is_numeric($supplierInput) ? Supplier::find($supplierInput) : null;

            if (!$supplier) {
                $supplier = Supplier::create([
                    'supplier_name' => $supplierInput,
                ]);
            }
            $finalSupplierId = $supplier->id;

            $grand_total = 0;
            $items = [];
            
            for ($i = 0; $i < count($request->product_id); $i++) {
                $qty = $request->quantity[$i];
                $price = $request->cost_price[$i];
                $productInput = $request->product_id[$i];

                $product = is_numeric($productInput) ? Product::find($productInput) : null;

                if (!$product) {
                    $product = Product::create([
                        'name'       => $productInput,
                        'slug'       => Str::slug($productInput) . '-' . time(),
                        'cost_price' => $price,
                        'base_price' => $price, 
                        'stock'      => 0,      
                    ]);
                }

                $subtotal = $qty * $price;
                $grand_total += $subtotal;

                $items[] = [
                    'product_id' => $product->id, // Gunakan ID produk (baik yang lama atau yang baru dibuat)
                    'quantity'   => $qty,
                    'cost_price' => $price,
                    'subtotal'   => $subtotal,
                ];
            }

            // Simpan Data Utama (Purchase)
            $purchase = Purchase::create([
                'invoice_number' => $request->invoice_number,
                'supplier_id'    => $finalSupplierId,
                'status'         => 'pending',
                'grand_total'    => $grand_total,
                'notes'          => $request->notes,
            ]);

            // Simpan Detail Barang (Purchase Items)
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