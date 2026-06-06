<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // Menampilkan daftar kategori
    public function index()
    {
        $categories = Category::latest()->get(); // Mengambil data dari terbaru
        return view('admin.pages.categories.index', compact('categories'));
    }

    // Menampilkan form tambah kategori
    public function create()
    {
        return view('admin.pages.categories.create');
    }

    // Menyimpan data kategori baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'category_name' => 'required|string|max:255|unique:categories,category_name',
            'description' => 'nullable|string',
        ], [
            'category_name.required' => 'Nama kategori wajib diisi.',
            'category_name.unique' => 'Nama kategori sudah digunakan.',
        ]);

        Category::create($request->all());

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil ditambahkan!');
    }

    // Menampilkan form edit kategori
    public function edit(Category $category)
    {
        return view('admin.pages.categories.edit', compact('category'));
    }

    // Memperbarui data kategori di database
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'category_name' => 'required|string|max:255|unique:categories,category_name,' . $category->id,
            'description' => 'nullable|string',
        ], [
            'category_name.required' => 'Nama kategori wajib diisi.',
            'category_name.unique' => 'Nama kategori sudah digunakan.',
        ]);

        $category->update($request->all());

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil diperbarui!');
    }

    // Menghapus data kategori
    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil dihapus!');
    }
}