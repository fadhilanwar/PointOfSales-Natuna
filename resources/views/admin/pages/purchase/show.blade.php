@extends('admin.layouts.app')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 flex items-center gap-2">
                Detail Transaksi Pembelian
                @if ($purchase->status == 'pending')
                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-amber-100 text-amber-700">Menunggu
                        Barang</span>
                @elseif($purchase->status == 'received')
                    <span
                        class="px-2.5 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-700">Diterima</span>
                @else
                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">Dibatalkan</span>
                @endif
            </h2>
            <p class="text-sm text-slate-500 mt-1">Invoice: <span
                    class="font-bold text-slate-700">{{ $purchase->invoice_number }}</span></p>
        </div>
        <a href="{{ route('admin.purchases.index') }}"
            class="text-sm font-medium text-slate-500 hover:text-[#0a7b8c] bg-white border border-slate-200 py-2 px-4 rounded-lg shadow-sm transition-all">Kembali</a>
    </div>

    @if (session('error'))
        <div class="mb-4 p-4 text-sm font-medium text-red-800 rounded-lg bg-red-50 border border-red-200">
            {{ session('error') }}</div>
    @endif

    @if (session('success'))
        <div class="mb-4 p-4 text-sm font-medium text-emerald-800 rounded-lg bg-emerald-50 border border-emerald-200">
            {{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="lg:col-span-1 space-y-6">
            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm relative overflow-hidden">
                <div class="absolute top-0 right-0 w-16 h-16 bg-cyan-50 rounded-bl-full z-0"></div>

                <h3
                    class="font-bold text-slate-800 mb-4 border-b border-slate-100 pb-3 relative z-10 uppercase tracking-wider text-xs">
                    Informasi Transaksi</h3>

                <div class="space-y-4 relative z-10 text-sm">
                    <div>
                        <span class="block text-slate-400 font-medium text-xs mb-1">Tanggal Pesanan</span>
                        <span
                            class="font-semibold text-slate-800">{{ \Carbon\Carbon::parse($purchase->created_at)->format('d F Y, H:i') }}</span>
                    </div>

                    <div>
                        <span class="block text-slate-400 font-medium text-xs mb-1">Tanggal Diterima</span>
                        @if ($purchase->status == 'received')
                            <span
                                class="font-semibold text-slate-800">{{ \Carbon\Carbon::parse($purchase->updated_at)->format('d F Y, H:i') }}</span>
                        @else
                            <span class="font-semibold text-slate-800">Barang belum diterima</span>
                        @endif
                    </div>
                    <div>
                        <span class="block text-slate-400 font-medium text-xs mb-1">Supplier / Pemasok</span>
                        <span
                            class="font-bold text-[#0a7b8c] text-base">{{ $purchase->supplier->supplier_name ?? 'Supplier Tidak Diketahui' }}</span>
                        <br>
                        <span class="text-xs text-black-400">Alamat: {{ $purchase->supplier->address ?? '-' }}</span>

                    </div>
                    @if ($purchase->supplier && $purchase->supplier->phone)
                        <div>
                            <span class="block text-slate-400 font-medium text-xs mb-1">No. Telepon Supplier</span>
                            <span class="font-semibold text-slate-800">{{ $purchase->supplier->phone }}</span>
                        </div>
                    @endif
                    @if ($purchase->notes)
                        <div>
                            <span class="block text-slate-400 font-medium text-xs mb-1">Catatan Tambahan</span>
                            <p class="font-medium text-slate-600 bg-slate-50 p-3 rounded-lg border border-slate-100 italic">
                                {{ $purchase->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>

            @if ($purchase->status == 'pending')
                <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-6 shadow-sm">
                    <h3 class="font-bold text-emerald-800 mb-2">Konfirmasi Barang</h3>
                    <p class="text-xs text-emerald-600 mb-4">Pastikan Anda telah mengecek fisik barang dari supplier.
                        Menekan tombol di bawah ini akan otomatis <b>menambah stok</b> di etalase Anda.</p>

                    <form action="{{ route('admin.purchases.receive', $purchase->id) }}" method="POST"
                        onsubmit="return confirm('Yakin ingin menyelesaikan pesanan ini? Stok produk akan otomatis bertambah.');">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                            class="w-full flex items-center justify-center gap-2 rounded-lg bg-emerald-500 py-3 px-5 text-sm font-bold text-white hover:bg-emerald-600 transition-all shadow-md hover:shadow-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Barang Diterima
                        </button>
                    </form>
                </div>
            @endif
        </div>

        <div class="lg:col-span-2">
            <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden flex flex-col h-full">
                <div class="p-5 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="font-bold text-slate-800">Daftar Barang ({{ $purchase->items->count() }} Item)</h3>
                </div>

                <div class="p-0 overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-white border-b border-slate-100 text-slate-400 text-xs uppercase tracking-wider">
                            <tr>
                                <th class="py-4 px-5 font-semibold">Produk</th>
                                <th class="py-4 px-5 font-semibold text-center">Qty</th>
                                <th class="py-4 px-5 font-semibold text-right">Harga Modal</th>
                                <th class="py-4 px-5 font-semibold text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($purchase->items as $item)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="py-4 px-5">
                                        <span
                                            class="font-bold text-slate-800 block">{{ $item->product->name ?? 'Produk Dihapus' }}</span>
                                        <span class="text-xs text-slate-400">Barcode:
                                            {{ $item->product->barcode ?? '-' }}</span>
                                    </td>
                                    <td class="py-4 px-5 text-center font-bold text-slate-700 bg-slate-50/50">
                                        {{ $item->quantity }}
                                    </td>
                                    <td class="py-4 px-5 text-right font-medium text-slate-500">
                                        Rp {{ number_format($item->cost_price, 0, ',', '.') }}
                                    </td>
                                    <td class="py-4 px-5 text-right font-bold text-slate-800">
                                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div
                    class="mt-auto border-t-2 border-dashed border-slate-200 bg-slate-50 p-6 flex justify-between items-center">
                    <span class="font-bold text-slate-500 uppercase tracking-widest text-sm">Grand Total</span>
                    <span class="text-2xl font-bold text-[#0a7b8c]">Rp
                        {{ number_format($purchase->grand_total, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>
@endsection
