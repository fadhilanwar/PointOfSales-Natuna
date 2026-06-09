@extends('admin.layouts.app')

@section('content')
<div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <div class="flex items-center gap-2 mb-1">
            <a href="{{ route('admin.debts.index') }}" class="text-slate-400 hover:text-[#0a7b8c] transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h2 class="text-2xl font-bold text-slate-800">Detail Tagihan</h2>
        </div>
        <p class="text-sm text-slate-500 ml-8">Rincian pesanan belum lunas atas nama <strong class="text-slate-700">{{ $user->name }}</strong>.</p>
    </div>
</div>

{{-- Card Ringkasan Total Hutang --}}
{{-- REVISI: Card Ringkasan Total Hutang (Warna dinamis: Merah jika ada hutang, Hijau jika Lunas) --}}
<div class="mb-6 border rounded-xl p-5 md:p-6 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4
    {{ $totalHutang > 0 ? 'bg-red-50 border-red-200' : 'bg-emerald-50 border-emerald-200' }}">
    
    <div>
        {{-- Teks label atas --}}
        <p class="text-xs font-bold uppercase tracking-wider mb-1 
            {{ $totalHutang > 0 ? 'text-red-500' : 'text-emerald-600' }}">
            Total Keseluruhan Hutang
        </p>
        {{-- Angka nominal utama --}}
        <p class="text-3xl font-black tracking-tight 
            {{ $totalHutang > 0 ? 'text-red-600' : 'text-emerald-700' }}">
            Rp {{ number_format($totalHutang, 0, ',', '.') }}
        </p>
    </div>

    {{-- Kotak informasi toko di sebelah kanan --}}
    <div class="text-left md:text-right p-3 rounded-lg border 
        {{ $totalHutang > 0 ? 'bg-white/60 border-red-100' : 'bg-white/60 border-emerald-100' }}">
        <p class="text-[11px] font-bold text-slate-500 uppercase">Toko / Instansi</p>
        <p class="text-sm font-bold text-slate-800">{{ $user->shop_name ?? 'Personal' }}</p>
        <p class="text-xs text-slate-500 mt-0.5">{{ $user->phone_number ?? '-' }}</p>
    </div>

</div>

<div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
    <div class="p-5 border-b border-slate-100 bg-slate-50/50">
        <h3 class="font-bold text-slate-800">Daftar Transaksi Belum Lunas</h3>
    </div>
    <div class="max-w-full overflow-x-auto">
        <table class="w-full text-sm text-left text-slate-500">
            <thead class="text-xs text-slate-500 uppercase bg-slate-50 border-b border-slate-100">
                <tr>
                    <th scope="col" class="py-4 px-6 font-semibold">No. Invoice & Tanggal</th>
                    <th scope="col" class="py-4 px-6 font-semibold text-right">Total Transaksi</th>
                    <th scope="col" class="py-4 px-6 font-semibold text-right">Sudah Dibayar</th>
                    <th scope="col" class="py-4 px-6 font-semibold text-right">Sisa Tagihan</th>
                    <th scope="col" class="py-4 px-6 font-semibold text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($orders as $order)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="py-4 px-6">
                        <p class="font-bold text-[#0a7b8c]">{{ $order->invoice_number }}</p>
                        <p class="text-xs text-slate-400 mt-0.5">{{ $order->created_at->translatedFormat('d M Y, H:i') }}</p>
                    </td>
                    <td class="py-4 px-6 text-right font-medium text-slate-700">
                        Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                    </td>
                    <td class="py-4 px-6 text-right font-medium text-emerald-600">
                        Rp {{ number_format($order->total_paid, 0, ',', '.') }}
                    </td>
                    <td class="py-4 px-6 text-right">
                        <span class="font-bold text-red-600">
                            Rp {{ number_format($order->remaining_debt, 0, ',', '.') }}
                        </span>
                    </td>
                    <td class="py-4 px-6 text-center">
                        {{-- Tombol menuju detail pesanan utuh (silakan sesuaikan route-nya jika ada) --}}
                        <a href="{{ route('admin.transactions.show', $order->id) }}" target="_blank" class="inline-block px-3 py-1.5 text-xs font-bold text-[#0a7b8c] bg-cyan-50 border border-cyan-100 rounded-lg hover:bg-[#0a7b8c] hover:text-white transition-colors">
                            Cek Order
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="py-8 text-center text-slate-500">Tidak ada transaksi yang menunggak.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection