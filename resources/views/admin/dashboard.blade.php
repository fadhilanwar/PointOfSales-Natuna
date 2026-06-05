@extends('admin.layouts.app')

@section('title', 'Dashboard - Natuna Grosir')
@section('header_title', 'Dashboard Statistik')

@section('content')
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        
        <div class="bg-white rounded-lg p-5 shadow-sm border border-gray-100 flex items-center">
            <div class="p-3 rounded-full bg-emerald-100 text-emerald-600 mr-4">
                <i class="fas fa-wallet fa-lg"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Penjualan Hari Ini</p>
                <p class="text-2xl font-bold text-gray-800">Rp 1.250.000</p>
            </div>
        </div>

        <div class="bg-white rounded-lg p-5 shadow-sm border border-gray-100 flex items-center">
            <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                <i class="fas fa-shopping-bag fa-lg"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Pesanan Selesai</p>
                <p class="text-2xl font-bold text-gray-800">24 Transaksi</p>
            </div>
        </div>

        <div class="bg-white rounded-lg p-5 shadow-sm border border-gray-100 flex items-center">
            <div class="p-3 rounded-full bg-indigo-100 text-indigo-600 mr-4">
                <i class="fas fa-boxes fa-lg"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Total Produk</p>
                <p class="text-2xl font-bold text-gray-800">145 Item</p>
            </div>
        </div>

        <div class="bg-white rounded-lg p-5 shadow-sm border border-gray-100 flex items-center">
            <div class="p-3 rounded-full bg-red-100 text-red-600 mr-4">
                <i class="fas fa-exclamation-triangle fa-lg"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Stok Tipis (< 5)</p>
                <p class="text-2xl font-bold text-red-600">8 Item</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                <h3 class="text-base font-semibold text-gray-800">Transaksi Terakhir</h3>
                <a href="#" class="text-sm text-indigo-600 hover:text-indigo-800">Lihat Semua</a>
            </div>
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr>
                                <th class="pb-3 text-sm font-semibold text-gray-600 border-b">Invoice</th>
                                <th class="pb-3 text-sm font-semibold text-gray-600 border-b">Tipe</th>
                                <th class="pb-3 text-sm font-semibold text-gray-600 border-b text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            <tr class="border-b">
                                <td class="py-3 text-gray-800">POS-260508-0005</td>
                                <td class="py-3"><span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs">Offline</span></td>
                                <td class="py-3 text-right font-medium text-gray-800">Rp 434.000</td>
                            </tr>
                            <tr class="border-b">
                                <td class="py-3 text-gray-800">WEB-260508-0006</td>
                                <td class="py-3"><span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs">Online</span></td>
                                <td class="py-3 text-right font-medium text-gray-800">Rp 51.000</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-red-50 flex justify-between items-center">
                <h3 class="text-base font-semibold text-red-800">Peringatan Stok Menipis</h3>
            </div>
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr>
                                <th class="pb-3 text-sm font-semibold text-gray-600 border-b">Nama Barang</th>
                                <th class="pb-3 text-sm font-semibold text-gray-600 border-b text-center">Sisa Stok</th>
                                <th class="pb-3 text-sm font-semibold text-gray-600 border-b text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            <tr class="border-b">
                                <td class="py-3 text-gray-800">Produk adipisci molestias</td>
                                <td class="py-3 text-center"><span class="font-bold text-red-600">1</span></td>
                                <td class="py-3 text-right">
                                    <button class="text-xs bg-indigo-50 text-indigo-600 px-3 py-1 rounded hover:bg-indigo-100">Restock</button>
                                </td>
                            </tr>
                            <tr class="border-b">
                                <td class="py-3 text-gray-800">Produk autem aperiam</td>
                                <td class="py-3 text-center"><span class="font-bold text-red-600">1</span></td>
                                <td class="py-3 text-right">
                                    <button class="text-xs bg-indigo-50 text-indigo-600 px-3 py-1 rounded hover:bg-indigo-100">Restock</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection