@extends('admin.layouts.app')

@section('content')
<div class="p-6 bg-slate-50 min-h-[calc(100vh-80px)] -m-6">
    <div class="mb-6">
        <h1 class="text-2xl font-extrabold text-slate-800">Manajemen Transaksi</h1>
        <p class="text-slate-500 text-sm mt-1">Pantau pesanan pelanggan, status pembayaran, dan proses pengiriman barang Natuna Grosir.</p>
    </div>

    <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-200 mb-6">
        <form action="{{ route('admin.transactions.index') }}" method="GET" class="flex flex-wrap items-end gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1.5">Status Pengiriman</label>
                <select name="delivery_status" class="w-48 bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-lg focus:border-[#0a7b8c] p-2.5 outline-none">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('delivery_status') == 'pending' ? 'selected' : '' }}>Menunggu Diproses</option>
                    <option value="processing" {{ request('delivery_status') == 'processing' ? 'selected' : '' }}>Sedang Dikemas</option>
                    <option value="shipping" {{ request('delivery_status') == 'shipping' ? 'selected' : '' }}>Dalam Pengiriman</option>
                    <option value="delivered" {{ request('delivery_status') == 'delivered' ? 'selected' : '' }}>Selesai / Diterima</option>
                    <option value="cancelled" {{ request('delivery_status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1.5">Status Pembayaran</label>
                <select name="payment_status" class="w-40 bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-lg focus:border-[#0a7b8c] p-2.5 outline-none">
                    <option value="">Semua</option>
                    <option value="belum_lunas" {{ request('payment_status') == 'belum_lunas' ? 'selected' : '' }}>Belum Lunas</option>
                    <option value="lunas" {{ request('payment_status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                </select>
            </div>
            <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white font-bold py-2.5 px-6 rounded-lg transition-colors">
                Filter Data
            </button>
            @if(request('delivery_status') || request('payment_status'))
                <a href="{{ route('admin.transactions.index') }}" class="text-slate-500 hover:text-red-500 font-semibold text-sm underline px-2">Reset</a>
            @endif
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-slate-600">
                <thead class="text-xs text-slate-500 uppercase bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4 font-bold">Invoice & Tanggal</th>
                        <th class="px-6 py-4 font-bold">Pelanggan</th>
                        <th class="px-6 py-4 font-bold text-right">Total Tagihan</th>
                        <th class="px-6 py-4 font-bold text-center">Pembayaran</th>
                        <th class="px-6 py-4 font-bold text-center">Pengiriman</th>
                        <th class="px-6 py-4 font-bold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $trx)
                    <tr class="border-b border-slate-100 hover:bg-slate-50">
                        <td class="px-6 py-4">
                            <div class="font-bold text-[#0a7b8c]">{{ $trx->invoice_number }}</div>
                            <div class="text-xs text-slate-400 mt-1">{{ \Carbon\Carbon::parse($trx->created_at)->format('d M Y, H:i') }}</div>
                        </td>
                        <td class="px-6 py-4 font-semibold text-slate-700">
                            {{ $trx->user?->name ?? 'Tamu / Pelanggan Umum' }}
                        </td>
                        <td class="px-6 py-4 font-extrabold text-slate-800 text-right">
                            Rp {{ number_format($trx->grand_total, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($trx->payment_status == 'lunas') 
                                <span class="bg-green-100 text-green-700 text-[10px] font-black px-3 py-1 rounded-full uppercase">Lunas</span>
                            @else 
                                <span class="bg-red-100 text-red-700 text-[10px] font-black px-3 py-1 rounded-full uppercase">Belum Lunas</span> 
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($trx->delivery_status == 'pending') 
                                <span class="bg-amber-100 text-amber-700 text-xs font-bold px-3 py-1 rounded-md">Pending</span>
                            @elseif($trx->delivery_status == 'processing') 
                                <span class="bg-blue-100 text-blue-700 text-xs font-bold px-3 py-1 rounded-md">Dikemas</span>
                            @elseif($trx->delivery_status == 'shipping') 
                                <span class="bg-purple-100 text-purple-700 text-xs font-bold px-3 py-1 rounded-md">Dikirim</span>
                            @elseif($trx->delivery_status == 'delivered') 
                                <span class="bg-emerald-100 text-emerald-700 text-xs font-bold px-3 py-1 rounded-md">Diterima</span>
                            @else 
                                <span class="bg-slate-200 text-slate-600 text-xs font-bold px-3 py-1 rounded-md">Batal</span> 
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('admin.transactions.show', $trx->id) }}" class="bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold py-2 px-4 rounded-lg transition-colors inline-block">
                                Cek Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-500 font-medium">
                            Belum ada transaksi yang sesuai dengan filter.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">
            {{ $transactions->links() }}
        </div>
    </div>
</div>
@endsection