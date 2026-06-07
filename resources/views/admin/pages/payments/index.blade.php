@extends('admin.layouts.app')

@section('content')

    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Verifikasi Pembayaran</h1>
        <p class="text-sm text-gray-500 mt-1 font-medium">Kelola bukti transfer dari pelanggan Natuna GAS.</p>
    </div>

    {{-- Notifikasi --}}
    @if (session('success'))
        <div class="mb-5 bg-emerald-50 border border-emerald-200 rounded-2xl p-4 flex items-center gap-3">
            <div class="w-7 h-7 bg-emerald-500 rounded-full flex items-center justify-center shrink-0">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <p class="text-sm font-semibold text-emerald-800">{{ session('success') }}</p>
        </div>
    @endif

    @if (session('error'))
        <div class="mb-5 bg-red-50 border border-red-200 rounded-2xl p-4">
            <p class="text-sm font-semibold text-red-800">{{ session('error') }}</p>
        </div>
    @endif

    {{-- Tab Navigasi --}}
    <div class="flex overflow-x-auto border-b border-gray-200 mb-6 gap-0">
        @php
            $tabs = [
                'menunggu' => 'Menunggu Verifikasi',
                'belum_upload' => 'Belum Upload',
                'disetujui' => 'Disetujui',
                'ditolak' => 'Ditolak',
                'semua' => 'Semua',
            ];
        @endphp

        @foreach ($tabs as $key => $label)
            <a href="{{ route('admin.payments.index', ['tab' => $key]) }}"
                class="relative whitespace-nowrap py-3 px-5 border-b-2 font-bold text-sm transition-colors duration-200 cursor-pointer
                {{ $activeTab === $key ? 'border-[#06728A] text-[#06728A]' : 'border-transparent text-gray-400 hover:text-gray-600' }}">
                {{ $label }}

                {{-- Badge count hanya untuk tab yang penting --}}
                @if ($key === 'menunggu' && $countMenunggu > 0)
                    <span class="ml-1.5 px-1.5 py-0.5 text-[10px] font-black bg-red-500 text-white rounded-full">
                        {{ $countMenunggu }}
                    </span>
                @elseif ($key === 'belum_upload' && $countBelumUpload > 0)
                    <span class="ml-1.5 px-1.5 py-0.5 text-[10px] font-black bg-orange-400 text-white rounded-full">
                        {{ $countBelumUpload }}
                    </span>
                @endif
            </a>
        @endforeach
    </div>

    {{-- Tabel Daftar Pembayaran --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        @if ($payments->isEmpty())
            <div class="py-20 text-center">
                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                </div>
                <p class="text-sm font-bold text-gray-400">Tidak ada data pembayaran di kategori ini.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50/50">
                            <th class="text-left px-5 py-3.5 text-[11px] font-bold text-gray-400 uppercase tracking-wider">
                                Invoice</th>
                            <th class="text-left px-5 py-3.5 text-[11px] font-bold text-gray-400 uppercase tracking-wider">
                                Pelanggan</th>
                            <th class="text-left px-5 py-3.5 text-[11px] font-bold text-gray-400 uppercase tracking-wider">
                                Nominal Klaim</th>
                            <th class="text-left px-5 py-3.5 text-[11px] font-bold text-gray-400 uppercase tracking-wider">
                                Grand Total</th>
                            <th class="text-left px-5 py-3.5 text-[11px] font-bold text-gray-400 uppercase tracking-wider">
                                Bukti</th>
                            <th class="text-left px-5 py-3.5 text-[11px] font-bold text-gray-400 uppercase tracking-wider">
                                Status</th>
                            <th class="text-left px-5 py-3.5 text-[11px] font-bold text-gray-400 uppercase tracking-wider">
                                Tanggal</th>
                            <th class="px-5 py-3.5"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach ($payments as $payment)
                            <tr class="hover:bg-gray-50/50 transition-colors group">

                                {{-- Invoice Number --}}
                                <td class="px-5 py-4">
                                    <p class="font-bold text-[#06728A] text-xs">{{ $payment->order->invoice_number }}</p>
                                    <p class="text-[10px] text-gray-400 font-medium mt-0.5">
                                        {{ $payment->type === 'full_payment' ? 'Bayar Penuh' : 'Cicilan' }}
                                    </p>
                                </td>

                                {{-- Pelanggan --}}
                                <td class="px-5 py-4">
                                    <p class="font-bold text-gray-800 text-xs">{{ $payment->order->user->name }}</p>
                                    @if ($payment->order->user->shop_name)
                                        <p class="text-[10px] text-gray-400 font-medium mt-0.5">
                                            {{ $payment->order->user->shop_name }}</p>
                                    @endif
                                </td>

                                {{-- Nominal Klaim (amount di tabel) --}}
                                <td class="px-5 py-4">
                                    <p class="font-black text-gray-900 text-sm">
                                        Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                    </p>
                                </td>

                                {{-- Grand Total Order --}}
                                <td class="px-5 py-4">
                                    <p class="font-bold text-gray-600 text-xs">
                                        Rp {{ number_format($payment->order->grand_total, 0, ',', '.') }}
                                    </p>
                                    {{-- Badge payment_status order --}}
                                    <span
                                        class="inline-block mt-1 px-2 py-0.5 rounded-full text-[9px] font-bold
                                        {{ $payment->order->payment_status === 'lunas' ? 'bg-emerald-100 text-emerald-700' : 'bg-orange-100 text-orange-700' }}">
                                        {{ $payment->order->payment_status === 'lunas' ? 'LUNAS' : 'BELUM LUNAS' }}
                                    </span>
                                </td>

                                {{-- Thumbnail Bukti Foto --}}
                                <td class="px-5 py-4">
                                    @if ($payment->payment_proof_path)
                                        <a href="{{ Storage::url($payment->payment_proof_path) }}" target="_blank"
                                            class="block w-12 h-12 rounded-xl overflow-hidden border-2 border-gray-200 hover:border-[#06728A] transition-colors">
                                            <img src="{{ Storage::url($payment->payment_proof_path) }}"
                                                alt="Bukti Transfer" class="w-full h-full object-cover">
                                        </a>
                                    @else
                                        <div
                                            class="w-12 h-12 rounded-xl bg-gray-100 border-2 border-dashed border-gray-200 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                        </div>
                                    @endif
                                </td>

                                {{-- Status Badge --}}
                                <td class="px-5 py-4">
                                    <span
                                        class="px-2.5 py-1 rounded-full text-[10px] font-black
                                        {{ $payment->status === 'approved'
                                            ? 'bg-emerald-100 text-emerald-700'
                                            : ($payment->status === 'rejected'
                                                ? 'bg-red-100 text-red-700'
                                                : 'bg-amber-100 text-amber-700') }}">
                                        {{ $payment->status === 'approved' ? 'DISETUJUI' : ($payment->status === 'rejected' ? 'DITOLAK' : 'MENUNGGU') }}
                                    </span>
                                </td>

                                {{-- Tanggal --}}
                                <td class="px-5 py-4">
                                    <p class="text-xs text-gray-500 font-medium">
                                        {{ $payment->created_at->translatedFormat('d M Y') }}
                                    </p>
                                    <p class="text-[10px] text-gray-400">{{ $payment->created_at->format('H:i') }}</p>
                                </td>

                                {{-- Aksi --}}
                                <td class="px-5 py-4">
                                    <a href="{{ route('admin.payments.show', $payment) }}"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-[#06728A] hover:bg-[#055c70] text-white text-xs font-bold rounded-lg transition-all active:scale-95">
                                        Proses
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($payments->hasPages())
                <div class="px-5 py-4 border-t border-gray-100">
                    {{ $payments->links() }}
                </div>
            @endif
        @endif
    </div>

@endsection
