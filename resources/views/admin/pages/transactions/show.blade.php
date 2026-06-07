@extends('admin.layouts.app')

@section('content')
<div class="p-6 bg-slate-50 min-h-[calc(100vh-80px)] -m-6">
    <!-- Header & Panel Logistik -->
    <div class="mb-6 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
        <a href="{{ route('admin.transactions.index') }}" class="flex items-center gap-2 text-slate-500 hover:text-slate-800 font-bold w-max">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg> Kembali
        </a>
        
        <div class="bg-white p-2 rounded-lg border border-slate-200 shadow-sm flex flex-wrap gap-2 items-center">
            <span class="text-xs font-bold text-slate-400 uppercase mr-2">Pengiriman:</span>
            
            @if($transaction->delivery_status == 'pending')
                <form action="{{ route('admin.transactions.delivery', $transaction->id) }}" method="POST">
                    @csrf @method('PATCH')
                    <input type="hidden" name="delivery_status" value="processing">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md text-sm">ACC & Siapkan Kemasan</button>
                </form>
            @elseif($transaction->delivery_status == 'processing')
                <form action="{{ route('admin.transactions.delivery', $transaction->id) }}" method="POST" class="flex gap-2 items-center">
                    @csrf @method('PATCH')
                    <input type="hidden" name="delivery_status" value="shipping">
                    <select name="courier_id" required class="text-sm border border-slate-300 rounded-md py-1.5 px-3 outline-none focus:border-[#0a7b8c]">
                        <option value="">-- Pilih Kurir --</option>
                        @foreach($couriers as $courier)
                            <option value="{{ $courier->id }}">{{ $courier->name }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded-md text-sm">Kirim Via Kurir</button>
                </form>
            @elseif($transaction->delivery_status == 'shipping')
                <form action="{{ route('admin.transactions.delivery', $transaction->id) }}" method="POST">
                    @csrf @method('PATCH')
                    <input type="hidden" name="delivery_status" value="delivered">
                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded-md text-sm">Barang Sampai</button>
                </form>
            @endif

            @if(!in_array($transaction->delivery_status, ['delivered', 'cancelled']))
                <form action="{{ route('admin.transactions.delivery', $transaction->id) }}" method="POST" onsubmit="return confirm('Yakin batalkan pesanan?');" class="ml-2">
                    @csrf @method('PATCH')
                    <input type="hidden" name="delivery_status" value="cancelled">
                    <button type="submit" class="bg-red-50 hover:bg-red-100 text-red-600 font-bold py-2 px-4 rounded-md text-sm border border-red-200">Batalkan</button>
                </form>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Kolom Info Pembeli & Pembayaran -->
        <div class="space-y-6 lg:col-span-1">
            <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
                <h3 class="font-bold text-slate-800 mb-3 border-b pb-2">Informasi Penerima</h3>
                <p class="font-black text-lg text-slate-900">{{ $transaction->user?->name ?? 'Tamu' }}</p>
                <p class="text-slate-600 mt-2 text-sm">{{ $transaction->shipping_address ?? '-' }}</p>
                
                @if($transaction->courier_id)
                    <div class="mt-4 p-3 bg-purple-50 border border-purple-100 rounded-lg text-sm text-purple-800">
                        <span class="font-bold uppercase text-xs">Diantar Oleh Kurir:</span> <br>
                        <span class="font-black">{{ $transaction->courier->name }}</span>
                    </div>
                @endif
            </div>

            <!-- PANEL VALIDASI PEMBAYARAN -->
            <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
                <div class="flex justify-between items-center mb-4 border-b pb-2">
                    <h3 class="font-bold text-slate-800">Keuangan & Bukti Bayar</h3>
                    @if($transaction->payment_status == 'lunas')
                        <span class="bg-green-100 text-green-700 text-[10px] font-black px-2 py-1 rounded uppercase">LUNAS</span>
                    @else
                        <span class="bg-red-100 text-red-700 text-[10px] font-black px-2 py-1 rounded uppercase">BELUM LUNAS</span>
                    @endif
                </div>

                @php $sisa = $transaction->grand_total - $transaction->total_paid; @endphp
                <div class="text-sm mb-5 p-3 bg-slate-50 rounded-lg border border-slate-100 space-y-2">
                    <div class="flex justify-between text-slate-600"><span>Grand Total:</span><span class="font-bold">Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}</span></div>
                    <div class="flex justify-between text-[#0a7b8c]"><span>Total Masuk (Valid):</span><span class="font-bold">Rp {{ number_format($transaction->total_paid, 0, ',', '.') }}</span></div>
                    <div class="flex justify-between border-t pt-2 {{ $sisa > 0 ? 'text-red-600' : 'text-green-600' }}"><span class="font-bold">Kekurangan:</span><span class="font-black">Rp {{ number_format($sisa > 0 ? $sisa : 0, 0, ',', '.') }}</span></div>
                </div>

                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Riwayat Unggahan / Cicilan</h4>
                <div class="space-y-4">
                    @forelse($transaction->payments as $payment)
                        <div class="border rounded-lg p-3 {{ $payment->status == 'pending' ? 'border-amber-300 bg-amber-50' : ($payment->status == 'approved' ? 'border-green-200 bg-green-50' : 'border-red-200 bg-red-50') }}">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-xs font-black uppercase {{ $payment->status == 'pending' ? 'text-amber-600' : ($payment->status == 'approved' ? 'text-green-600' : 'text-red-600') }}">{{ $payment->status }}</span>
                                <span class="text-[10px] font-bold text-slate-400 uppercase">{{ $payment->payment_method }} - {{ $payment->type ?? '?' }}</span>
                            </div>

                            @if($payment->payment_proof_path)
                                <a href="{{ asset('storage/' . $payment->payment_proof_path) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $payment->payment_proof_path) }}" class="w-full h-32 object-cover rounded-md mb-2 border border-slate-200 hover:opacity-80">
                                </a>
                            @endif

                            @if($payment->status == 'approved')
                                <p class="text-sm font-bold text-slate-800">Nominal ACC: Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                            @elseif($payment->status == 'rejected')
                                <p class="text-xs text-red-600 font-bold">Bukti ini ditolak admin.</p>
                            
                            <!-- FORM VALIDASI BUKTI TRANSFER (ACC / TOLAK) -->
                            @elseif($payment->status == 'pending')
                                <form action="{{ route('admin.transactions.verify_payment', [$transaction->id, $payment->id]) }}" method="POST" class="mt-3 border-t border-amber-200 pt-3">
                                    @csrf @method('PATCH')
                                    <label class="block text-xs font-bold text-slate-600 mb-1">Cek Gambar. Nominal Transfer Asli:</label>
                                    <input type="number" name="amount" value="{{ round($sisa) }}" max="{{ round($sisa) }}" class="w-full text-sm border border-slate-300 rounded p-2 mb-2 outline-none focus:border-[#0a7b8c]">
                                    
                                    <div class="flex gap-2">
                                        <button type="submit" name="action" value="approve" class="w-1/2 bg-green-600 hover:bg-green-700 text-white font-bold py-2 rounded text-xs">ACC Valid</button>
                                        <button type="submit" name="action" value="reject" class="w-1/2 bg-red-100 text-red-700 border border-red-200 font-bold py-2 rounded text-xs" onclick="return confirm('Yakin tolak?');">Tolak</button>
                                    </div>
                                </form>
                            @endif
                        </div>
                    @empty
                        <div class="text-center text-xs font-medium text-slate-400 p-4 border border-dashed rounded-lg">Belum ada data pembayaran/bukti.</div>
                    @endforelse
                </div>

                <!-- FORM INPUT MANUAL (Untuk setoran kurir COD / Susulan) -->
                @if($sisa > 0)
                    <form action="{{ route('admin.transactions.manual_payment', $transaction->id) }}" method="POST" class="mt-6 border-t border-slate-200 pt-4">
                        @csrf 
                        <h4 class="text-xs font-bold text-[#0a7b8c] uppercase tracking-wider mb-2">Input Pembayaran Tunai (COD) / Susulan</h4>
                        <input type="number" name="amount" min="1" max="{{ round($sisa) }}" placeholder="Nominal tunai yang diterima admin" required class="w-full text-sm border border-slate-300 rounded p-2 mb-3 outline-none focus:border-[#0a7b8c]">
                        <button type="submit" class="w-full bg-slate-800 hover:bg-slate-900 text-white font-bold py-2 rounded text-sm">Catat Uang Masuk</button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Kolom Rincian Produk (Kanan) -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden h-max">
            <div class="p-5 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                <div><h3 class="font-bold text-slate-800">Daftar Barang</h3><p class="text-xs text-slate-500">Invoice: {{ $transaction->invoice_number }}</p></div>
                <span class="font-bold px-3 py-1 rounded-full text-xs uppercase {{ $transaction->delivery_status == 'cancelled' ? 'bg-red-100 text-red-700' : 'bg-[#0a7b8c] text-white' }}">{{ $transaction->delivery_status }}</span>
            </div>
            
            <table class="w-full text-sm text-left text-slate-700">
                <thead class="text-xs text-slate-400 uppercase border-b border-slate-200">
                    <tr><th class="px-5 py-3 font-bold">Produk</th><th class="px-5 py-3 font-bold text-center">Qty</th><th class="px-5 py-3 font-bold text-right">Harga</th><th class="px-5 py-3 font-bold text-right">Subtotal</th></tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($transaction->items as $item)
                    <tr class="hover:bg-slate-50">
                        <td class="px-5 py-4 font-semibold">{{ $item->product?->name ?? '(Dihapus)' }}</td><td class="px-5 py-4 text-center">{{ $item->quantity }}</td><td class="px-5 py-4 text-right">Rp {{ number_format($item->price_at_time, 0, ',', '.') }}</td><td class="px-5 py-4 text-right font-bold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="p-5 border-t border-slate-200 bg-slate-50 text-right">
                <p class="text-xs text-slate-500 font-bold uppercase tracking-wider mb-1">Tagihan Keseluruhan</p>
                <p class="text-2xl font-black text-[#0a7b8c]">Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection