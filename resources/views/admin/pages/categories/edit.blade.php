@extends('admin.layouts.app')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <h2 class="text-2xl font-bold text-slate-800">Edit Kategori</h2>
    <a href="{{ route('admin.categories.index') }}" class="text-sm font-medium text-slate-500 hover:text-[#0a7b8c]">Kembali</a>
</div>

<div class="rounded-xl border border-slate-200 bg-white shadow-sm p-6 max-w-2xl">
    <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="mb-5">
            <label for="category_name" class="mb-2 block text-sm font-semibold text-slate-800">
                Nama Kategori <span class="text-red-500">*</span>
            </label>
            <input type="text" id="category_name" name="category_name" value="{{ old('category_name', $category->category_name) }}" 
                   class="w-full rounded-lg borde bg-transparent py-2.5 px-4 outline-none focus:border-[#0a7b8c] focus:ring-1 focus:ring-[#0a7b8c] @error('category_name') border-red-500 @enderror">
            @error('category_name')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="description" class="mb-2 block text-sm font-semibold text-slate-800">
                Deskripsi (Opsional)
            </label>
            <textarea id="description" name="description" rows="4" 
                      class="w-full rounded-lg border border-slate-300 bg-transparent py-2.5 px-4 outline-none focus:border-[#0a7b8c] focus:ring-1 focus:ring-[#0a7b8c]">{{ old('description', $category->description) }}</textarea>
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="rounded-lg bg-[#0a7b8c] py-2.5 px-6 font-medium text-white hover:bg-[#075e6b] transition-all">
                Perbarui Data
            </button>
            <a href="{{ route('admin.categories.index') }}" class="rounded-lg bg-slate-100 py-2.5 px-6 font-medium text-slate-600 hover:bg-slate-200 transition-all">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection