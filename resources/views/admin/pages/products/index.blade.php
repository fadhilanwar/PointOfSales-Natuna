@extends('admin.layouts.app')

@section('content')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Data Produk</h2>
            <p class="text-sm text-slate-500 mt-1">Kelola daftar produk, harga, dan stok barang Anda.</p>
        </div>
        <button
            class="inline-flex items-center justify-center gap-2 rounded-lg bg-[#0a7b8c] py-2.5 px-5 text-sm font-semibold text-white shadow-sm hover:bg-[#075e6b] hover:shadow-md transition-all duration-200">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Tambah Data
        </button>
    </div>

    <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">

        <div class="p-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
            <div class="relative max-w-sm w-full">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text"
                    class="bg-white border border-slate-200 text-slate-800 text-sm rounded-lg focus:ring-[#0a7b8c] focus:border-[#0a7b8c] block w-full pl-10 p-2.5 outline-none transition-all"
                    placeholder="Cari nama produk...">
            </div>
        </div>

        <div class="max-w-full overflow-x-auto">
            <table class="w-full text-sm text-left text-slate-500">
                <thead class="text-xs text-slate-500 uppercase bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th scope="col" class="py-4 px-6 font-semibold">Produk</th>
                        <th scope="col" class="py-4 px-6 font-semibold">Kategori</th>
                        <th scope="col" class="py-4 px-6 font-semibold">Harga</th>
                        <th scope="col" class="py-4 px-6 font-semibold">Stok</th>
                        <th scope="col" class="py-4 px-6 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <tr class="hover:bg-slate-50 transition-colors duration-200 group">
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-4">
                                {{-- @if ($product->image_path)
                                    <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}"
                                        class="h-10 w-10 shrink-0 rounded-lg object-cover border border-slate-200 shadow-sm">
                                @else --}}
                                    <div
                                        class="h-10 w-10 shrink-0 rounded-lg bg-slate-100 flex items-center justify-center text-slate-400 border border-slate-200">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                        </svg>
                                    </div>
                                {{-- @endif --}}
                                <div>
                                    <h3 class="font-semibold text-slate-800">Beras Maknyuss 5kg</h3>
                                    <span class="text-xs text-slate-400 block mt-0.5">SKU: BR-001</span>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <span
                                class="inline-flex items-center rounded-full bg-blue-50 px-2.5 py-1 text-xs font-medium text-blue-600 ring-1 ring-inset ring-blue-500/10">
                                Sembako
                            </span>
                        </td>
                        <td class="py-4 px-6">
                            <span class="font-medium text-slate-800">Rp 65.000</span>
                        </td>
                        <td class="py-4 px-6">
                            <div
                                class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-600/20">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                Tersedia (50)
                            </div>
                        </td>
                        <td class="py-4 px-6 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button
                                    class="p-2 rounded-lg text-slate-400 hover:text-[#0a7b8c] hover:bg-cyan-50 transition-all"
                                    title="Edit Data">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                </button>
                                <button
                                    class="p-2 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 transition-all"
                                    title="Hapus Data">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
