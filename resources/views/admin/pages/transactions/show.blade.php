@extends('admin.layouts.app')

@section('content')
<div class="p-6 bg-slate-50 min-h-[calc(100vh-80px)] -m-6">
    <div class="mb-6 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
        <a href="{{ route('admin.transactions.index') }}" class="flex items-center gap-2 text-slate-500 hover:text-slate-800 font-bold transition-colors w-max">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg> Kembali
        </a>
        
        <div class="bg-white p-2 rounded-lg border border-slate-200 shadow-sm flex flex-wrap gap-2">
            
            @if($transaction->payment_status == 'belum_lunas')
                <form action="{{ route('admin.transactions.status', $transaction->id) }}" method="POST">
                    @csrf @method('PATCH')
                    <input type="hidden" name="payment_status" value="lunas">
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-md text-sm shadow-sm">Tandai Lunas</button>
                </form>
            @endif

            @if($transaction->delivery_status == 'pending')
                <form action="{{ route('admin.transactions.status', $transaction->id) }}" method="POST">
                    @csrf @method('PATCH')
                    <input type="hidden" name="delivery_status" value="processing">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md text-sm shadow-sm">Proses Kemas Barang</button>
                </form>
            @elseif($transaction->delivery_status == 'processing')
                <form action="{{ route('admin.transactions.status', $transaction->id) }}" method="POST">
                    @csrf @method('PATCH')
                    <input type="hidden" name="delivery_status" value="shipping">
                    <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded-md text-sm shadow-sm">Kirim Barang</button>
                </form>
            @elseif($transaction->delivery_status == 'shipping')
                <form action="{{ route('admin.transactions.status', $transaction->id) }}" method="POST">
                    @csrf @method('PATCH')
                    <input type="hidden" name="delivery_status" value="delivered">
                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded-md text-sm shadow-sm">Selesaikan Pesanan</button>
                </form>
            @endif

            @if(!in_array($transaction->delivery_status, ['delivered', 'cancelled']))
                <form action="{{ route('admin.transactions.status', $transaction->id) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan pesanan? Stok produk akan otomatis dikembalikan ke etalase.');">
                    @csrf @method('PATCH')
                    <input type="hidden" name="delivery_status" value="cancelled">
                    <button type="submit" class="bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 font-bold py-2 px-4 rounded-md text-sm shadow-sm">Batalkan Pesanan</button>
                </form>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="space-y-6 lg:col-span-1">
            <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
                <h3 class="font-bold text-slate-800 mb-4 border-b pb-2 flex justify-between items-center">
                    Data Pengiriman
                    @if($transaction->payment_status == 'lunas')
                        <span class="bg-green-100 text-green-700 text-[10px] font-black px-2 py-1 rounded uppercase tracking-wider">Lunas</span>
                    @else
                        <span class="bg-red-100 text-red-700 text-[10px] font-black px-2 py-1 rounded uppercase tracking-wider">Belum Lunas</span>
                    @endif
                </h3>
                
                <p class="font-black text-lg text-slate-900">{{ $transaction->user?->name ?? 'Tamu / Pelanggan Umum' }}</p>
                <p class="text-slate-600 mt-2 text-sm leading-relaxed">{{ $transaction->shipping_address ?? 'Alamat tidak diatur / Pembelian di Tempat' }}</p>
                <p class="text-[#0a7b8c] font-bold mt-2 text-sm">{{ $transaction->user?->phone ?? '-' }}</p>
            </div>
        </div>

        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                <div>
                    <h3 class="font-bold text-slate-800">Daftar Barang</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Invoice: {{ $transaction->invoice_number }}</p>
                </div>
                <span class="font-bold px-3 py-1 rounded-full text-xs uppercase shadow-sm
                    {{ $transaction->delivery_status == 'cancelled' ? 'bg-red-100 text-red-700' : 'bg-[#0a7b8c] text-white' }}">
                    {{ $transaction->delivery_status }}
                </span>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-slate-700">
                    <thead class="text-xs text-slate-400 uppercase border-b border-slate-200">
                        <tr>
                            <th class="px-5 py-3 font-bold">Produk</th>
                            <th class="px-5 py-3 font-bold text-center">Qty</th>
                            <th class="px-5 py-3 font-bold text-right">Harga</th>
                            <th class="px-5 py-3 font-bold text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($transaction->items as $item)
                        <tr class="hover:bg-slate-50">
                            <td class="px-5 py-4 font-semibold">{{ $item->product?->name ?? '(Produk telah dihapus)' }}</td>
                            <td class="px-5 py-4 text-center">{{ $item->quantity }}</td>
                            <td class="px-5 py-4 text-right">Rp {{ number_format($item->price_at_time, 0, ',', '.') }}</td>
                            <td class="px-5 py-4 text-right font-bold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-5 py-4 text-center text-slate-500">Rincian produk tidak ditemukan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-5 border-t border-slate-200 bg-slate-50 flex justify-end">
                <div class="text-right">
                    <p class="text-xs text-slate-500 font-bold uppercase tracking-wider mb-1">Grand Total</p>
                    <p class="text-2xl font-black text-[#0a7b8c]">Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection