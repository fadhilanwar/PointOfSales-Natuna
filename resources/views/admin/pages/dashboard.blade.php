@extends('admin.layouts.app')

@section('content')
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-2xl font-bold text-slate-800">
        Dashboard Utama
    </h2>
</div>

<div class="grid grid-cols-1 gap-4 md:grid-cols-2 md:gap-6 xl:grid-cols-4 2xl:gap-7.5 mb-6">
    
    <div class="rounded-sm border border-slate-200 bg-white py-6 px-7 shadow-sm">
        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-[#0a7b8c]">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
            </svg>
        </div>
        <div class="mt-4 flex items-end justify-between">
            <div>
                <h4 class="text-2xl font-bold text-slate-800">1,250</h4>
                <span class="text-sm font-medium text-slate-500">Total Pengguna</span>
            </div>
            <span class="flex items-center gap-1 text-sm font-medium text-emerald-500">
                +2.5%
            </span>
        </div>
    </div>

    <div class="rounded-sm border border-slate-200 bg-white py-6 px-7 shadow-sm">
        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-[#0a7b8c]">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
            </svg>
        </div>
        <div class="mt-4 flex items-end justify-between">
            <div>
                <h4 class="text-2xl font-bold text-slate-800">24</h4>
                <span class="text-sm font-medium text-slate-500">Kategori Produk</span>
            </div>
        </div>
    </div>

    <div class="rounded-sm border border-slate-200 bg-white py-6 px-7 shadow-sm">
        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-[#0a7b8c]">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
            </svg>
        </div>
        <div class="mt-4 flex items-end justify-between">
            <div>
                <h4 class="text-2xl font-bold text-slate-800">435</h4>
                <span class="text-sm font-medium text-slate-500">Total Produk</span>
            </div>
            <span class="flex items-center gap-1 text-sm font-medium text-emerald-500">
                +18 Baru
            </span>
        </div>
    </div>

    <div class="rounded-sm border border-slate-200 bg-white py-6 px-7 shadow-sm">
        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-[#0a7b8c]">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
        </div>
        <div class="mt-4 flex items-end justify-between">
            <div>
                <h4 class="text-2xl font-bold text-slate-800">Rp 12.5M</h4>
                <span class="text-sm font-medium text-slate-500">Penjualan Bulan Ini</span>
            </div>
        </div>
    </div>
</div>

<div class="rounded-sm border border-slate-200 bg-white px-5 pt-6 pb-2.5 shadow-sm sm:px-7.5 xl:pb-1">
    <h4 class="mb-6 text-xl font-bold text-slate-800">
        Produk Baru Ditambahkan
    </h4>

    <div class="max-w-full overflow-x-auto">
        <table class="w-full table-auto">
            <thead>
                <tr class="bg-slate-50 text-left">
                    <th class="py-4 px-4 font-medium text-slate-800">Nama Produk</th>
                    <th class="py-4 px-4 font-medium text-slate-800">Kategori</th>
                    <th class="py-4 px-4 font-medium text-slate-800">Harga</th>
                    <th class="py-4 px-4 font-medium text-slate-800">Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="border-b border-slate-100 py-5 px-4">
                        <p class="font-medium text-slate-800">Minyak Goreng Bimoli 2L</p>
                    </td>
                    <td class="border-b border-slate-100 py-5 px-4">
                        <p class="text-slate-500">Sembako</p>
                    </td>
                    <td class="border-b border-slate-100 py-5 px-4">
                        <p class="text-slate-800">Rp 35.000</p>
                    </td>
                    <td class="border-b border-slate-100 py-5 px-4">
                        <span class="inline-flex rounded-full bg-emerald-100 py-1 px-3 text-sm font-medium text-emerald-600">
                            Tersedia
                        </span>
                    </td>
                </tr>
                <tr>
                    <td class="border-b border-slate-100 py-5 px-4">
                        <p class="font-medium text-slate-800">Gula Pasir Gulaku 1Kg</p>
                    </td>
                    <td class="border-b border-slate-100 py-5 px-4">
                        <p class="text-slate-500">Sembako</p>
                    </td>
                    <td class="border-b border-slate-100 py-5 px-4">
                        <p class="text-slate-800">Rp 16.500</p>
                    </td>
                    <td class="border-b border-slate-100 py-5 px-4">
                        <span class="inline-flex rounded-full bg-red-100 py-1 px-3 text-sm font-medium text-red-600">
                            Stok Menipis
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection