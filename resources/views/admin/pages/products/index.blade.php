@extends('admin.layouts.app')

@section('content')
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-2xl font-bold text-slate-800">
        Data Produk
    </h2>
    <button class="inline-flex items-center justify-center rounded bg-[#0a7b8c] py-2 px-6 text-center font-medium text-white hover:bg-[#075e6b] transition">
        + Tambah Data
    </button>
</div>

<div class="rounded-sm border border-slate-200 bg-white px-5 pt-6 pb-2.5 shadow-sm sm:px-7.5 xl:pb-1">
    <div class="max-w-full overflow-x-auto">
        <table class="w-full table-auto">
            <thead>
                <tr class="bg-slate-50 text-left">
                    <th class="py-4 px-4 font-medium text-slate-800 xl:pl-11">Nama Produk</th>
                    <th class="py-4 px-4 font-medium text-slate-800">Kategori</th>
                    <th class="py-4 px-4 font-medium text-slate-800">Harga</th>
                    <th class="py-4 px-4 font-medium text-slate-800">Stok</th>
                    <th class="py-4 px-4 font-medium text-slate-800 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="border-b border-[#eee] py-5 px-4 pl-9 xl:pl-11">
                        <p class="font-medium text-slate-800">Beras Maknyuss 5kg</p>
                    </td>
                    <td class="border-b border-[#eee] py-5 px-4">
                        <p class="text-slate-500">Sembako</p>
                    </td>
                    <td class="border-b border-[#eee] py-5 px-4">
                        <p class="text-slate-800">Rp 65.000</p>
                    </td>
                    <td class="border-b border-[#eee] py-5 px-4">
                        <p class="text-emerald-500">Tersedia (50)</p>
                    </td>
                    <td class="border-b border-[#eee] py-5 px-4 text-center">
                        <div class="flex items-center justify-center space-x-3.5">
                            <button class="hover:text-[#0a7b8c]" title="Edit">
                                Edit
                            </button>
                            <button class="hover:text-red-500" title="Hapus">
                                Hapus
                            </button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection