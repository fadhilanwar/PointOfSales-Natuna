<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category; // <-- Pastikan ini ada
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {

    $categories = Category::all();



        if ($categories->isEmpty()) {
            
           return response("<script>
                alert('Harap isi Data Kategori terlebih dahulu!');
                window.location.href = '" . route('admin.categories.create') . "';
            </script>");
        }

        $products = Product::with('category')->latest()->get();
        return view('admin.pages.product.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all(); // Ambil semua kategori
        return view('admin.pages.product.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id', // <-- Validasi Kategori
            'barcode' => 'nullable|string|unique:products,barcode',
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'cost_price' => 'required|numeric|min:0',
            'base_price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'low_stock_threshold' => 'required|integer|min:0',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('products', 'public');
        }

        Product::create($data);

        return redirect()->route('admin.products.index')->with('success', 'Data produk berhasil ditambahkan!');
    }

    // Ubah parameter menjadi $id
    public function edit($id)
    {
        // Cari manual berdasarkan ID
        $product = Product::findOrFail($id); 
        $categories = Category::all(); 

        return view('admin.pages.product.edit', compact('product', 'categories'));
    }

    // Ubah parameter menjadi $id
    public function update(Request $request, $id)
    {
        // Cari manual berdasarkan ID
        $product = Product::findOrFail($id);

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'barcode' => 'nullable|string|unique:products,barcode,' . $product->id,
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'cost_price' => 'required|numeric|min:0',
            'base_price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'low_stock_threshold' => 'required|integer|min:0',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            if ($product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }
            $data['image_path'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('admin.products.index')->with('success', 'Data produk berhasil diperbarui!');
    }

    // Ubah parameter menjadi $id
    public function destroy($id)
    {
        // Cari manual berdasarkan ID
        $product = Product::findOrFail($id);

        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }
        $product->delete();
        
        return redirect()->route('admin.products.index')->with('success', 'Data produk berhasil dihapus!');
    }
}