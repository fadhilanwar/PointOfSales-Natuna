<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::latest()->get();
        // Tambahkan .pages di path-nya
        return view('admin.pages.categories.index', compact('categories')); 
    }

    public function create()
    {
        // Tambahkan .pages di path-nya
        return view('admin.pages.categories.create');
    }

    // ... method store tidak perlu diubah karena pakai redirect route ...

    public function edit(Category $category)
    {
        // Tambahkan .pages di path-nya
        return view('admin.pages.categories.edit', compact('category'));
    }
}