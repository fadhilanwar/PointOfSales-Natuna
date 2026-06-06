@extends('admin.layouts.app')

@section('content')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Transaksi Pembelian (PO)</h2>
            <p class="text-sm text-slate-500 mt-1">Kelola data barang masuk dari Supplier.</p>
        </div>
        <a href="{{ route('admin.purchases.create') }}"
            class="inline-flex items-center justify-center gap-2 rounded-lg bg-[#0a7b8c] py-2.5 px-5 text-sm font-semibold text-white shadow-sm hover:bg-[#075e6b] transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Buat Transaksi Baru
        </a>
    </div>

    @if (session('success'))
        <div class="mb-4 p-4 text-sm font-medium text-emerald-800 rounded-lg bg-emerald-50 border border-emerald-200">
            {{ session('success') }}</div>
    @endif

    <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
        <div class="max-w-full overflow-x-auto">
            <table class="w-full text-sm text-left text-slate-500">
                <thead class="text-xs text-slate-500 uppercase bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="py-4 px-6 font-semibold">No. Invoice & Tanggal</th>
                        <th class="py-4 px-6 font-semibold">Supplier</th>
                        <th class="py-4 px-6 font-semibold">Status</th>
                        <th class="py-4 px-6 font-semibold">Grand Total</th>
                        <th class="py-4 px-6 font-semibold">Aksi</th>
                        <th class="py-4 px-6 font-semibold text-center">Detail Pesanan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($purchases as $po)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-4 px-6">
                                <span class="font-bold text-slate-800 block">{{ $po->invoice_number }}</span>
                                <span
                                    class="text-xs text-slate-400">{{ \Carbon\Carbon::parse($po->created_at)->format('d M Y H:i') }}</span>
                            </td>
                            <td class="py-4 px-6 font-medium text-slate-700">
                                {{ $po->supplier->supplier_name ?? 'Supplier Dihapus' }}</td>
                            <td class="py-4 px-6">
                                @if ($po->status == 'pending')
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-full bg-amber-50 text-xs font-medium text-amber-700 ring-1 ring-inset ring-amber-600/20">Menunggu
                                        Barang</span>
                                @elseif($po->status == 'received')
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-full bg-emerald-50 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-600/20">Diterima</span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-full bg-red-50 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/20">Dibatalkan</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 font-bold text-slate-800">
                                Rp {{ number_format($po->grand_total, 0, ',', '.') }}
                            </td>

                            <td class="py-4 px-6">
                                @if ($po->status == 'pending')
                                    <form action="{{ route('admin.purchases.receive', $po->id) }}" method="POST"
                                        onsubmit="return confirm('Apakah barang dari supplier ini sudah benar-benar sampai di gudang? Stok akan otomatis bertambah.');">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-500 py-1.5 px-3 text-xs font-semibold text-white hover:bg-emerald-600 transition-all shadow-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Terima Barang
                                        </button>
                                    </form>
                                @else
                                    <button
                                        class="inline-flex items-center gap-1.5 rounded-lg bg-slate-100 py-1.5 px-3 text-xs font-semibold text-slate-400 cursor-not-allowed">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Selesai
                                    </button>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-center">
                                <a href="{{ route('admin.purchases.show', $po->id) }}"
                                    class="inline-flex items-center gap-1.5 rounded-lg bg-blue-500 border border-slate-200 py-1.5 px-3 text-xs font-semibold text-white hover:bg-blue-400 transition-all shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="15px" height="15px"
                                        viewBox="0 0 24 24" fill="none">
                                        <path
                                            d="M8 6L21 6.00078M8 12L21 12.0008M8 18L21 18.0007M3 6.5H4V5.5H3V6.5ZM3 12.5H4V11.5H3V12.5ZM3 18.5H4V17.5H3V18.5Z"
                                            stroke="#ffff" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                    Detail Pesanan
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-slate-500">Belum ada transaksi pembelian.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
