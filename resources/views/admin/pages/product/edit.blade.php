@extends('admin.layouts.app')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Edit Produk</h2>
        <a href="{{ route('admin.products.index') }}"
            class="text-sm font-medium text-slate-500 hover:text-[#0a7b8c] transition-colors">Kembali</a>
    </div>

    <div class="rounded-xl border border-slate-200 bg-white shadow-sm p-6 max-w-4xl">
        <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="space-y-5">
                    <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider border-b pb-2">Informasi Produk
                    </h3>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-800">Barcode <span
                                class="text-slate-400 font-normal">(Opsional)</span></label>
                        <input type="text" name="barcode" value="{{ old('barcode', $product->barcode) }}"
                            placeholder="Masukan Barcode"
                            class="w-full rounded-lg border border-slate-300 py-2.5 px-4 outline-none focus:border-[#0a7b8c] focus:ring-1 focus:ring-[#0a7b8c] font-medium">
                        @error('barcode')
                            <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-800">Nama Produk <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $product->name) }}" placeholder="Minyak Goreng 2 Liter"
                            class="w-full rounded-lg border border-slate-300 py-2.5 px-4 outline-none focus:border-[#0a7b8c] focus:ring-1 focus:ring-[#0a7b8c] font-medium"
                            required>
                        @error('name')
                            <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="space-y-5">
                    <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider border-b pb-2">Harga & Ketersediaan
                    </h3>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-800">Harga Modal <span
                                    class="text-red-500">*</span></label>
                            <div class="relative">
                                <span
                                    class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 font-semibold">Rp</span>
                                <input type="number" name="cost_price" value="{{ old('cost_price', $product->cost_price) }}"
                                    class="w-full rounded-lg border border-slate-300 py-2.5 pl-12 pr-4 outline-none focus:border-[#0a7b8c] focus:ring-1 focus:ring-[#0a7b8c] font-medium"
                                    required>
                            </div>
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-800">Harga Jual <span
                                    class="text-red-500">*</span></label>
                            <div class="relative">
                                <span
                                    class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 font-semibold">Rp</span>
                                <input type="number" name="base_price" value="{{ old('base_price', $product->base_price) }}"
                                    class="w-full rounded-lg border border-slate-300 py-2.5 pl-12 pr-4 outline-none focus:border-[#0a7b8c] focus:ring-1 focus:ring-[#0a7b8c] font-medium"
                                    required>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-800">Stok Saat Ini <span
                                    class="text-red-500">*</span></label>
                            <input type="number" name="stock" value="{{ old('stock', $product->stock) }}"
                                class="w-full rounded-lg border border-slate-300 py-2.5 px-4 outline-none focus:border-[#0a7b8c] focus:ring-1 focus:ring-[#0a7b8c] font-medium"
                                required>
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-800">Batas Stok Minimum <span
                                    class="text-red-500">*</span></label>
                            <input type="number" name="low_stock_threshold" value="{{ old('low_stock_threshold', $product->low_stock_threshold) }}"
                                class="w-full rounded-lg border border-slate-300 py-2.5 px-4 outline-none focus:border-[#0a7b8c] focus:ring-1 focus:ring-[#0a7b8c] font-medium text-red-500"
                                required>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-800">Kategori
                        <span class="text-slate-400 font-normal">(Opsional)</span>
                        <select name="category_id"
                            class="w-full rounded-lg border border-slate-300 py-2.5 px-4 outline-none focus:border-[#0a7b8c] font-medium mt-1">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->category_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                        @enderror
                    </label>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-800">Foto Produk <span
                            class="text-slate-400 font-normal">(Opsional, biarkan kosong jika tidak diubah)</span></label>

                    <input type="file" name="image" id="image"
                        accept="image/png, image/jpeg, image/jpg, image/webp" onchange="previewImage()"
                        class="w-full rounded-lg border border-slate-300 py-2 px-3 outline-none focus:border-[#0a7b8c] text-sm file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-cyan-50 file:text-[#0a7b8c] hover:file:bg-cyan-100">
                    @error('image')
                        <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-3 p-5 font-semibold text-xs border border-slate-100 rounded-lg w-fit bg-slate-50 mt-4">
                <h2 class="mb-2">Preview Gambar:</h2>
                @if($product->image_path)
                    <img id="img-preview" src="{{ asset('storage/' . $product->image_path) }}" class="h-32 w-32 rounded-lg object-cover border border-slate-200 shadow-sm"
                        alt="Preview">
                @else
                    <img id="img-preview" class="h-32 w-32 rounded-lg object-cover border border-slate-200 shadow-sm hidden"
                        alt="Preview">
                @endif
            </div>

            <div class="flex justify-end border-t pt-6 mt-6">
                <button type="submit"
                    class="rounded-lg cursor-pointer bg-[#0a7b8c] py-2.5 px-8 font-semibold text-white hover:bg-[#075e6b] transition-all shadow-sm">Simpan Perubahan</button>
            </div>
        </form>
    </div>

    <script>
        function previewImage() {
            const image = document.querySelector('#image');
            const imgPreview = document.querySelector('#img-preview');

            if (image.files && image.files[0]) {
                const oFReader = new FileReader();
                oFReader.readAsDataURL(image.files[0]);

                oFReader.onload = function(oFREvent) {
                    imgPreview.src = oFREvent.target.result;
                    imgPreview.classList.remove('hidden');
                }
            }
        }
    </script>
@endsection