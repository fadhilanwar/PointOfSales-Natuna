@extends('admin.layouts.app')

@section('content')
<div class="p-6 bg-slate-50 min-h-[calc(100vh-80px)] -m-6">
    <div class="mb-6 flex justify-between items-end">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-800">Rekap Penjualan (Orders)</h1>
            <p class="text-slate-500 text-sm mt-1">Laporan riwayat transaksi dari website dan kasir POS.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="p-3 bg-emerald-100 text-emerald-600 rounded-lg"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
            <div><p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Pendapatan</p><h3 class="text-2xl font-black text-slate-800">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3></div>
        </div>
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="p-3 bg-blue-100 text-blue-600 rounded-lg"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg></div>
            <div><p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Transaksi</p><h3 class="text-2xl font-black text-slate-800">{{ $totalOrders }} Pesanan</h3></div>
        </div>
    </div>

    <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-200 mb-6 print:hidden">
        <form method="GET" action="{{ route('report.transaction') }}" class="flex flex-wrap gap-4 items-end">
            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1.5">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="bg-slate-50 border border-slate-200 text-sm rounded-lg p-2.5 outline-none w-40 focus:border-[#0a7b8c]">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1.5">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="bg-slate-50 border border-slate-200 text-sm rounded-lg p-2.5 outline-none w-40 focus:border-[#0a7b8c]">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="bg-[#0a7b8c] hover:bg-[#075e6b] text-white font-bold py-2.5 px-5 rounded-lg text-sm transition-colors">Filter Range</button>
                <a href="{{ route('report.transaction') }}" class="bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold py-2.5 px-5 rounded-lg text-sm transition-colors">Reset</a>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <table class="w-full text-sm text-left text-slate-600">
            <thead class="text-xs text-slate-500 uppercase bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-6 py-4 font-bold">Waktu & Invoice</th>
                    <th class="px-6 py-4 font-bold">Pelanggan</th>
                    <th class="px-6 py-4 font-bold text-center">Keuangan</th>
                    <th class="px-6 py-4 font-bold text-center">Pengiriman</th>
                    <th class="px-6 py-4 font-bold text-right">Grand Total</th>
                    <th class="px-6 py-4 font-bold text-center print:hidden">Detail</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($transactions as $t)
                <tr class="hover:bg-slate-50">
                    <td class="px-6 py-4"><div class="font-bold text-[#0a7b8c]">{{ $t->invoice_number }}</div><div class="text-xs text-slate-400 mt-0.5">{{ \Carbon\Carbon::parse($t->created_at)->format('d M Y, H:i') }}</div></td>
                    <td class="px-6 py-4 font-semibold text-slate-700">{{ $t->user?->name ?? 'Pelanggan Kasir (Guest)' }}</td>
                    <td class="px-6 py-4 text-center">
                        @if($t->payment_status == 'lunas') <span class="bg-green-100 text-green-700 text-[10px] font-black px-2 py-1 rounded uppercase">Lunas</span>
                        @else <span class="bg-red-100 text-red-700 text-[10px] font-black px-2 py-1 rounded uppercase">Belum Lunas</span> @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="bg-slate-100 text-slate-600 text-[10px] border border-slate-200 font-bold px-2 py-1 rounded uppercase">{{ $t->delivery_status }}</span>
                    </td>
                    <td class="px-6 py-4 font-extrabold text-slate-800 text-right">Rp {{ number_format($t->grand_total, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 text-center print:hidden">
                        <a href="{{ route('report.transaction.show', $t->id) }}" class="inline-flex items-center gap-1.5 rounded-lg bg-blue-500 border border-slate-200 py-1.5 px-3 text-xs font-semibold text-white hover:bg-blue-400 transition-all shadow-sm">Detail Order</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="p-8 text-center text-slate-500">Tidak ada data penjualan pada periode tanggal ini.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection