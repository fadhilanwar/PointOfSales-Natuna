@extends('layouts.app')

@section('content')
    <div class="px-4 py-6 md:py-10 max-w-4xl mx-auto min-h-screen">

        <!-- Header Halaman dengan Tombol Back Khusus Desktop & Mobile -->
        <div class="flex items-center gap-3 mb-6 justify-center md:justify-start">
            <a href="{{ url('/') }}"
                class="hidden md:block p-2 -ml-2 text-gray-500 hover:text-[#06728A] hover:bg-gray-100 rounded-full transition-all duration-300 cursor-pointer"
                title="Kembali ke Beranda">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <h1 class="text-2xl font-bold text-[#06728A] tracking-tight cursor-default">
                Riwayat Pesanan
            </h1>
        </div>

        <!-- Form Pencarian (Search Bar) -->
        <form action="{{ route('orders.index') }}" method="GET" class="mb-6 relative">
            <input type="hidden" name="tab" value="{{ $activeTab }}">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <input type="text" name="search" value="{{ request('search') }}"
                class="w-full bg-white border border-gray-200 focus:border-[#06728A] rounded-2xl py-3.5 pl-12 pr-4 text-sm font-medium focus:outline-none focus:ring-4 focus:ring-[#06728A]/10 shadow-sm transition-all"
                placeholder="Cari nomor invoice pesanan...">
        </form>

        <!-- Navigasi Tab Status -->
        <div class="flex overflow-x-auto border-b border-gray-200 mb-6 custom-scrollbar">
            @php
                $tabs = [
                    'semua' => 'Semua',
                    'belum_diproses' => 'Belum Diproses',
                    'sedang_dikirim' => 'Sedang Dikirim',
                    'belum_lunas' => 'Belum Lunas',
                    'selesai' => 'Selesai',
                ];
            @endphp

            @foreach ($tabs as $key => $label)
                <a href="{{ route('orders.index', ['tab' => $key, 'search' => request('search')]) }}"
                    class="whitespace-nowrap py-3 px-5 border-b-2 font-bold text-sm transition-colors duration-200 cursor-pointer
               {{ $activeTab === $key ? 'border-[#06728A] text-[#06728A]' : 'border-transparent text-gray-400 hover:text-[#06728A]' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        <!-- Area Daftar Pesanan (Card Loop) -->
        <div class="space-y-4">
            @forelse($orders as $order)
                @php
                    if (in_array($order->status, ['pending_approval', 'approved', 'paid'])) {
                        $badgeClass = 'bg-[#CBE4ED] text-[#06728A]';
                        $badgeText = 'BELUM DIPROSES';
                    } elseif ($order->status === 'shipping') {
                        $badgeClass = 'bg-amber-400 text-white';
                        $badgeText = 'SEDANG DIKIRIM';
                    } elseif ($order->status === 'awaiting_payment') {
                        $badgeClass = 'bg-red-100 text-red-600';
                        $badgeText = 'BELUM LUNAS';
                    } elseif ($order->status === 'completed') {
                        $badgeClass = 'bg-emerald-500 text-white';
                        $badgeText = 'SELESAI';
                    } else {
                        $badgeClass = 'bg-gray-200 text-gray-600';
                        $badgeText = strtoupper(str_replace('_', ' ', $order->status));
                    }
                @endphp

                <!-- Desain Card -->
                <div
                    class="bg-white rounded-2xl p-5 md:p-6 shadow-sm border border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-5 transition-all hover:shadow-md">

                    <div class="space-y-2">
                        <div class="flex items-center justify-between md:justify-start gap-4">
                            <p class="text-xs text-gray-500 font-medium cursor-default">
                                {{ $order->created_at->translatedFormat('d M Y') }}</p>
                            <span
                                class="px-3 py-1 rounded-full text-[10px] font-bold tracking-wider cursor-default {{ $badgeClass }}">
                                {{ $badgeText }}
                            </span>
                        </div>

                        <h3 class="text-lg font-bold text-[#06728A] cursor-text">{{ $order->invoice_number }}</h3>
                        <p class="text-sm font-bold text-[#06728A] cursor-text">Total: Rp
                            {{ number_format($order->grand_total, 0, ',', '.') }}</p>

                        @if (in_array($order->status, ['shipping', 'completed']) || $order->courier_id)
                            <p class="text-xs text-gray-500 cursor-default">Kurir:
                                {{ $order->courier->name ?? 'Robert Sulaiman' }}</p>
                        @endif
                    </div>

                    <div class="flex flex-col sm:flex-row gap-2 mt-2 md:mt-0 w-full md:w-auto shrink-0">
                        @if ($order->status === 'awaiting_payment' && $order->payment_method === 'transfer')
                            <a href="{{ route('checkout.payment', $order) }}"
                                class="flex-1 md:flex-none flex items-center justify-center bg-orange-500 hover:bg-orange-600 text-white px-5 py-2.5 rounded-xl text-sm font-bold transition-all shadow-sm active:scale-95 cursor-pointer">
                                Unggah Bukti Bayar
                            </a>
                        @endif

                        <!-- Menggunakan invoice_number untuk URL detail pesanan agar aman -->
                        <a href="{{ route('orders.show', $order->invoice_number) }}"
                            class="flex-1 md:flex-none flex items-center justify-center bg-white border-2 border-gray-100 text-gray-700 hover:border-[#06728A] hover:text-[#06728A] hover:bg-[#F0F8FA] px-5 py-2.5 rounded-xl text-sm font-bold transition-all shadow-sm active:scale-95 cursor-pointer">
                            Lihat Detail
                        </a>
                    </div>
                </div>

            @empty
                <!-- EMPTY STATE UI -->
                <div
                    class="bg-white rounded-3xl p-10 flex flex-col items-center justify-center text-center shadow-sm border border-gray-100 mt-4">
                    <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mb-5">
                        <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2 cursor-default">Belum Ada Pesanan</h3>
                    <p class="text-sm text-gray-500 mb-6 max-w-xs cursor-default">Kamu belum membuat pesanan apapun di
                        kategori ini. Yuk, mulai belanja kebutuhan grosirmu sekarang!</p>
                    <a href="{{ url('/') }}"
                        class="bg-[#06728A] hover:bg-[#055c70] text-white px-8 py-3 rounded-xl text-sm font-bold transition-all shadow-md shadow-[#06728A]/20 active:scale-95 cursor-pointer">
                        Mulai Belanja
                    </a>
                </div>
            @endforelse
        </div>

    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            height: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
@endsection
