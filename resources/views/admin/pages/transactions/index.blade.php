@extends('admin.layouts.app')

@section('content')
<div class="p-6 bg-slate-50 min-h-[calc(100vh-80px)] -m-6">
    <div class="mb-6">
        <h1 class="text-2xl font-extrabold text-slate-800">Manajemen Transaksi Masuk</h1>
        <p class="text-slate-500 text-sm mt-1">Validasi bukti transfer, catat angsuran COD, dan atur kurir.</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <table class="w-full text-sm text-left text-slate-600">
            <thead class="text-xs text-slate-500 uppercase bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-6 py-4 font-bold">Invoice / Tanggal</th>
                    <th class="px-6 py-4 font-bold">Pelanggan & Total</th>
                    <th class="px-6 py-4 font-bold text-center">Status Validasi Admin</th>
                    <th class="px-6 py-4 font-bold text-center">Keuangan</th>
                    <th class="px-6 py-4 font-bold text-center">Logistik</th>
                    <th class="px-6 py-4 font-bold text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $trx)
                <tr class="border-b border-slate-100 hover:bg-slate-50">
                    <td class="px-6 py-4">
                        <div class="font-bold text-[#0a7b8c]">{{ $trx->invoice_number }}</div>
                        <div class="text-xs text-slate-400">{{ \Carbon\Carbon::parse($trx->created_at)->format('d M Y, H:i') }}</div>
                    </td>
                    <td class="px-6 py-4 font-semibold text-slate-700">
                        {{ $trx->user?->name ?? 'Tamu' }}
                        <div class="text-xs text-slate-500 mt-1 font-bold">Rp {{ number_format($trx->grand_total, 0, ',', '.') }}</div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        @php $pendingPayment = $trx->payments->where('status', 'pending')->first(); @endphp
                        
                        @if($pendingPayment)
                            <span class="bg-amber-100 text-amber-700 text-[10px] font-black px-2 py-1 rounded uppercase animate-pulse border border-amber-300">
                                ⚠️ ADA BUKTI BARU (CEK!)
                            </span>
                        @else
                            <span class="text-[10px] text-slate-400 uppercase font-bold">Aman / Tdk Ada Pending</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($trx->payment_status == 'lunas') <span class="bg-green-100 text-green-700 text-[10px] font-black px-3 py-1 rounded-full uppercase">Lunas</span>
                        @else <span class="bg-red-100 text-red-700 text-[10px] font-black px-3 py-1 rounded-full uppercase">Belum Lunas</span> @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="bg-slate-50 border border-slate-200 text-slate-700 text-[10px] font-bold px-3 py-1 rounded-md uppercase">{{ $trx->delivery_status }}</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <a href="{{ route('admin.transactions.show', $trx->id) }}" class="bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold py-2 px-4 rounded-lg transition-colors">Cek & Proses</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-12 text-center text-slate-500">Belum ada transaksi.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4 border-t">{{ $transactions->links() }}</div>
    </div>
</div>
@endsection