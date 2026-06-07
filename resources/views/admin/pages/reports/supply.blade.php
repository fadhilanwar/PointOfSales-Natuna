@extends('admin.layouts.app')

@section('content')
<div class="p-6 bg-slate-50 min-h-[calc(100vh-80px)] -m-6">
    <div class="mb-6 flex justify-between items-end">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-800">Laporan Suplai Barang</h1>
            <p class="text-slate-500 text-sm mt-1">Rekapitulasi pengeluaran untuk pembelian stok dari Supplier.</p>
        </div>
        <button onclick="window.print()" class="bg-slate-800 hover:bg-slate-900 text-white font-bold py-2 px-4 rounded-lg text-sm transition-colors flex items-center gap-2 print:hidden">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg> Cetak PDF
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="p-3 bg-red-100 text-red-600 rounded-lg"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path></svg></div>
            <div><p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Pengeluaran Suplai</p><h3 class="text-2xl font-black text-slate-800">Rp {{ number_format($totalSpending, 0, ',', '.') }}</h3></div>
        </div>
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="p-3 bg-purple-100 text-purple-600 rounded-lg"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg></div>
            <div><p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Frekuensi Pembelian</p><h3 class="text-2xl font-black text-slate-800">{{ $totalSupplies }} Faktur</h3></div>
        </div>
    </div>

    <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-200 mb-6 print:hidden">
        <form method="GET" action="{{ route('report.supply') }}" class="flex gap-4 items-end">
            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1.5">Bulan</label>
                <select name="month" class="bg-slate-50 border border-slate-200 text-sm rounded-lg p-2.5 outline-none w-40">
                    <option value="">Semua Bulan</option>
                    @foreach(range(1, 12) as $m)
                        <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>{{ date('F', mktime(0,0,0,$m,1)) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1.5">Tahun</label>
                <input type="number" name="year" value="{{ request('year', date('Y')) }}" class="bg-slate-50 border border-slate-200 text-sm rounded-lg p-2.5 outline-none w-32">
            </div>
            <button type="submit" class="bg-[#0a7b8c] hover:bg-[#075e6b] text-white font-bold py-2.5 px-5 rounded-lg text-sm transition-colors">Terapkan Filter</button>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <table class="w-full text-sm text-left text-slate-600">
            <thead class="text-xs text-slate-500 uppercase bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-6 py-4 font-bold">Tanggal & Invoice</th>
                    <th class="px-6 py-4 font-bold">Mitra Supplier</th>
                    <th class="px-6 py-4 font-bold text-center">Status Barang</th>
                    <th class="px-6 py-4 font-bold text-right">Total Biaya</th>
                    <th class="px-6 py-4 font-bold text-center print:hidden">Detail</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($supplies as $s)
                <tr class="hover:bg-slate-50">
                    <td class="px-6 py-4"><div class="font-bold text-purple-700">{{ $s->invoice_number }}</div><div class="text-xs text-slate-400 mt-0.5">{{ \Carbon\Carbon::parse($s->created_at)->format('d M Y') }}</div></td>
                    <td class="px-6 py-4 font-semibold text-slate-700">{{ $s->supplier->supplier_name }}</td>
                    <td class="px-6 py-4 text-center">
                        @if($s->status == 'received') <span class="bg-green-100 text-green-700 text-[10px] font-black px-2 py-1 rounded uppercase">Diterima</span>
                        @elseif($s->status == 'pending') <span class="bg-amber-100 text-amber-700 text-[10px] font-black px-2 py-1 rounded uppercase">Menunggu</span>
                        @else <span class="bg-red-100 text-red-700 text-[10px] font-black px-2 py-1 rounded uppercase">Dibatalkan</span> @endif
                    </td>
                    <td class="px-6 py-4 font-extrabold text-slate-800 text-right">Rp {{ number_format($s->grand_total, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 text-center print:hidden">
                        <a href="{{ route('report.supply.show', $s->id) }}" class="text-purple-600 font-bold hover:underline">Lihat Rincian</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="p-8 text-center text-slate-500">Tidak ada data pembelian/suplai pada periode ini.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection