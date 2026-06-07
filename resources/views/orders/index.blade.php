@extends('layouts.app')

@section('content')
    <div class="px-4 py-6 md:py-10 max-w-4xl mx-auto min-h-screen">

        <div class="flex items-center gap-3 mb-6 justify-center md:justify-start">
            <a href="{{ url('/') }}"
                class="hidden md:block p-2 -ml-2 text-gray-500 hover:text-[#06728A] hover:bg-gray-100 rounded-full transition-all duration-300 cursor-pointer"
                title="Kembali ke Beranda">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <h1 class="text-2xl font-bold text-[#06728A] tracking-tight cursor-default">Riwayat Pesanan</h1>
        </div>

        {{-- Search Bar --}}
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

        {{-- Tab Navigasi (disesuaikan dengan kolom delivery_status & payment_status baru) --}}
        <div class="flex overflow-x-auto border-b border-gray-200 mb-6 custom-scrollbar">
            @php
                $tabs = [
                    'semua' => 'Semua',
                    'diproses' => 'Diproses',
                    'dikirim' => 'Sedang Dikirim',
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

        {{-- Daftar Pesanan --}}
        <div class="space-y-4">
            @forelse($orders as $order)
                @php
                    // Badge disesuaikan dengan nilai delivery_status dan payment_status dari migration baru:
                    // delivery_status: pending, processing, shipping, delivered, cancelled
                    // payment_status: belum_lunas, lunas

                    if ($order->delivery_status === 'cancelled') {
                        $badgeClass = 'bg-red-100 text-red-600';
                        $badgeText = 'DIBATALKAN';
                    } elseif ($order->delivery_status === 'delivered' && $order->payment_status === 'lunas') {
                        $badgeClass = 'bg-emerald-500 text-white';
                        $badgeText = 'SELESAI';
                    } elseif ($order->delivery_status === 'delivered' && $order->payment_status === 'belum_lunas') {
                        // Barang sudah sampai tapi masih ada sisa tagihan
                        $badgeClass = 'bg-orange-100 text-orange-700';
                        $badgeText = 'BELUM LUNAS';
                    } elseif ($order->delivery_status === 'shipping') {
                        $badgeClass = 'bg-amber-400 text-white';
                        $badgeText = 'SEDANG DIKIRIM';
                    } elseif ($order->delivery_status === 'processing') {
                        $badgeClass = 'bg-blue-100 text-blue-700';
                        $badgeText = 'DIPROSES';
                    } else {
                        // 'pending': menunggu konfirmasi admin
                        $badgeClass = 'bg-[#CBE4ED] text-[#06728A]';
                        $badgeText = 'MENUNGGU KONFIRMASI';
                    }

                    // Ambil metode bayar dari payment pertama (sekarang ada di order_payments, bukan orders)
                    $firstPayment = $order->payments->first();
                @endphp

                <div
                    class="bg-white rounded-2xl p-5 md:p-6 shadow-sm border border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-5 transition-all hover:shadow-md">

                    <div class="space-y-2">
                        <div class="flex items-center justify-between md:justify-start gap-4">
                            <p class="text-xs text-gray-500 font-medium cursor-default">
                                {{ $order->created_at->translatedFormat('d M Y') }}
                            </p>
                            <span
                                class="px-3 py-1 rounded-full text-[10px] font-bold tracking-wider cursor-default {{ $badgeClass }}">
                                {{ $badgeText }}
                            </span>
                        </div>

                        <h3 class="text-lg font-bold text-[#06728A] cursor-text">{{ $order->invoice_number }}</h3>

                        <p class="text-sm font-bold text-gray-700 cursor-text">
                            Total: Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                        </p>

                        {{-- Tampilkan sisa tagihan kalau belum lunas --}}
                        @if ($order->payment_status === 'belum_lunas')
                            @php $sisaTagihan = $order->grand_total - $order->total_paid; @endphp
                            <p class="text-xs font-medium text-orange-600 cursor-default">
                                Sisa: Rp {{ number_format($sisaTagihan, 0, ',', '.') }}
                            </p>
                        @endif

                        {{-- Metode bayar diambil dari relasi payments --}}
                        @if ($firstPayment)
                            <p class="text-xs text-gray-400 cursor-default">
                                {{ $firstPayment->payment_method === 'transfer' ? 'Transfer Bank' : 'COD' }}
                            </p>
                        @endif
                    </div>

                    <div class="flex flex-col sm:flex-row gap-2 mt-2 md:mt-0 w-full md:w-auto shrink-0">

                        {{-- Tombol Upload Bukti: muncul kalau belum lunas, metode transfer,
                             dan tidak ada payment pending yang sudah diupload --}}
                        @php
                            $adaPendingUpload =
                                $order->payments->where('status', 'pending')->whereNull('payment_proof_path')->count() >
                                0;
                        @endphp

                        @if ($order->payment_status === 'belum_lunas' && $firstPayment?->payment_method === 'transfer' && $adaPendingUpload)
                            <a href="{{ route('checkout.payment', $order->invoice_number) }}"
                                class="flex-1 md:flex-none flex items-center justify-center bg-orange-500 hover:bg-orange-600 text-white px-5 py-2.5 rounded-xl text-sm font-bold transition-all shadow-sm active:scale-95 cursor-pointer">
                                Unggah Bukti Bayar
                            </a>
                        @endif

                        <a href="{{ route('orders.show', $order->invoice_number) }}"
                            class="flex-1 md:flex-none flex items-center justify-center bg-white border-2 border-gray-100 text-gray-700 hover:border-[#06728A] hover:text-[#06728A] hover:bg-[#F0F8FA] px-5 py-2.5 rounded-xl text-sm font-bold transition-all shadow-sm active:scale-95 cursor-pointer">
                            Lihat Detail
                        </a>
                    </div>
                </div>

            @empty
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
                    <p class="text-sm text-gray-500 mb-6 max-w-xs cursor-default">
                        Kamu belum membuat pesanan apapun di kategori ini. Yuk, mulai belanja kebutuhan grosirmu!
                    </p>
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
