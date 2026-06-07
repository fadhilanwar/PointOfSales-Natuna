@extends('admin.layouts.app')

@section('content')
<div class="p-6 bg-slate-50 min-h-[calc(100vh-80px)] -m-6">
    <div class="mb-6 flex justify-between items-center print:hidden">
        <a href="{{ route('report.transaction') }}" class="flex items-center gap-2 text-slate-500 hover:text-slate-800 font-bold w-max">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg> Kembali ke Rekap
        </a>
        <button onclick="window.print()" class="bg-slate-800 hover:bg-slate-900 text-white font-bold py-2 px-4 rounded-lg text-sm">
            Cetak Dokumen
        </button>
    </div>

    <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm mb-6 flex justify-between items-start">
        <div>
            <h2 class="text-2xl font-black text-slate-800">DOKUMEN PESANAN / INVOICE</h2>
            <p class="text-slate-500 text-sm mt-1">Nomor: <span class="font-bold text-[#0a7b8c]">{{ $transaction->invoice_number }}</span></p>
            <p class="text-slate-500 text-sm mt-1">Tanggal: <span class="font-semibold text-slate-700">{{ \Carbon\Carbon::parse($transaction->created_at)->format('d F Y, H:i') }}</span></p>
        </div>
        <div class="text-right flex flex-col gap-2">
            <div>
                <span class="text-xs font-bold text-slate-500 block mb-1">Status Keuangan</span>
                @if($transaction->payment_status == 'lunas') <span class="bg-green-100 text-green-700 font-black px-3 py-1 rounded uppercase text-sm border border-green-200">LUNAS</span>
                @else <span class="bg-red-100 text-red-700 font-black px-3 py-1 rounded uppercase text-sm border border-red-200">BELUM LUNAS</span> @endif
            </div>
            <div>
                <span class="text-xs font-bold text-slate-500 block mb-1 mt-2">Status Pengiriman</span>
                <span class="bg-slate-100 text-slate-700 font-black px-3 py-1 rounded uppercase text-sm border border-slate-200">{{ $transaction->delivery_status }}</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
            <h3 class="font-bold text-slate-800 mb-3 border-b pb-2 text-sm uppercase tracking-wide">Data Pelanggan</h3>
            <p class="font-black text-lg text-slate-900">{{ $transaction->user?->name ?? 'Tamu / Pelanggan Umum' }}</p>
            <p class="text-slate-600 mt-2 text-sm">{{ $transaction->shipping_address ?? 'Pembelian di Toko (POS)' }}</p>
            <p class="text-[#0a7b8c] font-bold mt-2 text-sm">{{ $transaction->user?->phone ?? '-' }}</p>
            
            @if($transaction->courier_id)
                <div class="mt-4 p-3 bg-purple-50 border border-purple-100 rounded-lg text-sm text-purple-800">
                    <span class="font-bold uppercase text-xs">Diantar Oleh Kurir:</span> <br>
                    <span class="font-black">{{ $transaction->courier->name }} ({{ $transaction->courier->vehicle_number }})</span>
                </div>
            @endif
        </div>

        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
            <h3 class="font-bold text-slate-800 mb-3 border-b pb-2 text-sm uppercase tracking-wide">Ringkasan Pembayaran</h3>
            
            @php $sisa = $transaction->grand_total - $transaction->total_paid; @endphp
            <div class="space-y-3 text-sm">
                <div class="flex justify-between text-slate-600"><span>Grand Total:</span><span class="font-bold">Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}</span></div>
                <div class="flex justify-between text-[#0a7b8c]"><span>Total Masuk (Valid):</span><span class="font-bold">Rp {{ number_format($transaction->total_paid, 0, ',', '.') }}</span></div>
                <div class="flex justify-between border-t border-slate-100 pt-3 {{ $sisa > 0 ? 'text-red-600' : 'text-green-600' }}"><span class="font-bold">Kekurangan:</span><span class="font-black text-base">Rp {{ number_format($sisa > 0 ? $sisa : 0, 0, ',', '.') }}</span></div>
            </div>

            <div class="mt-4 pt-3 border-t border-slate-100">
                <p class="text-xs font-bold text-slate-400 uppercase mb-2">Riwayat Validasi Dana:</p>
                @forelse($transaction->payments->where('status', 'approved') as $pay)
                    <div class="flex justify-between text-xs text-slate-600 bg-slate-50 p-2 rounded mb-1">
                        <span class="uppercase font-bold">{{ $pay->payment_method }} - {{ $pay->type }}</span>
                        <span class="font-black">Rp {{ number_format($pay->amount, 0, ',', '.') }}</span>
                    </div>
                @empty
                    <p class="text-xs text-red-500 font-semibold italic">Belum ada dana masuk yang divalidasi.</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-4 border-b border-slate-100 bg-slate-50">
            <h3 class="font-bold text-slate-800">Rincian Barang yang Dibeli</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-slate-700">
                <thead class="text-xs text-slate-400 uppercase border-b border-slate-200">
                    <tr>
                        <th class="px-5 py-3 font-bold">Produk</th>
                        <th class="px-5 py-3 font-bold text-center">Jumlah (Qty)</th>
                        <th class="px-5 py-3 font-bold text-right">Harga Satuan</th>
                        <th class="px-5 py-3 font-bold text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($transaction->items as $item)
                    <tr class="hover:bg-slate-50">
                        <td class="px-5 py-4 font-semibold">{{ $item->product?->name ?? '(Produk telah dihapus)' }}</td>
                        <td class="px-5 py-4 text-center font-bold">{{ $item->quantity }}</td>
                        <td class="px-5 py-4 text-right text-slate-500">Rp {{ number_format($item->price_at_time, 0, ',', '.') }}</td>
                        <td class="px-5 py-4 text-right font-bold text-slate-800">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-5 border-t-2 border-slate-200 bg-slate-50 text-right">
            <p class="text-xs text-slate-500 font-bold uppercase tracking-wider mb-1">TOTAL KESELURUHAN</p>
            <p class="text-3xl font-black text-[#0a7b8c]">Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}</p>
        </div>
    </div>
</div>
@endsection