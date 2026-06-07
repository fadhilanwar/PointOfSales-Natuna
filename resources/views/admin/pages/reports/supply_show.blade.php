@extends('admin.layouts.app')

@section('content')
<div class="p-6 bg-slate-50 min-h-[calc(100vh-80px)] -m-6">
    <div class="mb-6 flex justify-between items-center">
        <a href="{{ route('report.supply') }}" class="flex items-center gap-2 text-slate-500 hover:text-slate-800 font-bold w-max">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg> Kembali ke Laporan
        </a>
        {{-- <button onclick="window.print()" class="bg-slate-800 hover:bg-slate-900 text-white font-bold py-2 px-4 rounded-lg text-sm print:hidden">
            Cetak Faktur
        </button> --}}
    </div>

    <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm mb-6 flex justify-between items-start">
        <div>
            <h2 class="text-2xl font-black text-slate-800">FAKTUR PEMBELIAN (SUPLAI)</h2>
            <p class="text-slate-500 text-sm mt-1">Nomor: <span class="font-bold text-purple-700">{{ $supply->invoice_number }}</span></p>
        </div>
        <div class="text-right">
            <p class="text-sm font-bold text-slate-600 mb-1">Status Barang</p>
            @if($supply->status == 'received') <span class="bg-green-100 text-green-700 font-black px-3 py-1.5 rounded uppercase">DITERIMA</span>
            @elseif($supply->status == 'pending') <span class="bg-amber-100 text-amber-700 font-black px-3 py-1.5 rounded uppercase">MENUNGGU PENGIRIMAN</span>
            @else <span class="bg-red-100 text-red-700 font-black px-3 py-1.5 rounded uppercase">DIBATALKAN</span> @endif
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
            <h3 class="font-bold text-slate-800 mb-3 border-b pb-2 text-sm uppercase tracking-wide">Informasi Supplier</h3>
            <p class="font-black text-lg text-slate-900">{{ $supply->supplier->supplier_name }}</p>
            <p class="text-slate-600 mt-2 text-sm">{{ $supply->supplier->address ?? 'Alamat tidak dicatat' }}</p>
            <p class="text-[#0a7b8c] font-bold mt-2 text-sm">{{ $supply->supplier->phone ?? 'Tidak ada kontak' }}</p>
        </div>

        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
            <h3 class="font-bold text-slate-800 mb-3 border-b pb-2 text-sm uppercase tracking-wide">Detail & Catatan</h3>
            <div class="mb-3">
                <p class="text-xs text-slate-500 font-bold mb-0.5">Tanggal Pembelian</p>
                <p class="text-sm font-semibold text-slate-800">{{ \Carbon\Carbon::parse($supply->created_at)->format('l, d F Y - H:i') }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-500 font-bold mb-0.5">Catatan Admin</p>
                <p class="text-sm text-slate-700 italic bg-slate-50 p-2 rounded border border-slate-100">{{ $supply->notes ?? 'Tidak ada catatan khusus.' }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-4 border-b border-slate-100 bg-slate-50">
            <h3 class="font-bold text-slate-800">Rincian Barang Masuk</h3>
        </div>
        <table class="w-full text-sm text-left text-slate-700">
            <thead class="text-xs text-slate-400 uppercase border-b border-slate-200">
                <tr>
                    <th class="px-5 py-3 font-bold">Nama Produk</th>
                    <th class="px-5 py-3 font-bold text-center">Jumlah (Qty)</th>
                    <th class="px-5 py-3 font-bold text-right">Harga Modal / Unit</th>
                    <th class="px-5 py-3 font-bold text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($supply->items as $item)
                <tr class="hover:bg-slate-50">
                    <td class="px-5 py-4 font-semibold">{{ $item->product?->name ?? '(Produk telah dihapus)' }}</td>
                    <td class="px-5 py-4 text-center font-bold">{{ $item->quantity }}</td>
                    <td class="px-5 py-4 text-right text-slate-500">Rp {{ number_format($item->cost_price, 0, ',', '.') }}</td>
                    <td class="px-5 py-4 text-right font-bold text-slate-800">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-5 border-t-2 border-slate-200 bg-slate-50 text-right">
            <p class="text-xs text-slate-500 font-bold uppercase tracking-wider mb-1">Total Pengeluaran</p>
            <p class="text-3xl font-black text-red-600">Rp {{ number_format($supply->grand_total, 0, ',', '.') }}</p>
        </div>
    </div>
</div>
@endsection