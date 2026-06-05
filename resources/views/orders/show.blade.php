@extends('layouts.app')

@section('content')
    <div class="px-4 py-6 md:py-8 max-w-2xl mx-auto min-h-screen relative">

        <!-- Header Navigasi -->
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('orders.index') }}"
                class="p-2 -ml-2 text-gray-500 hover:text-[#06728A] hover:bg-gray-100 rounded-full transition-all duration-300 cursor-pointer">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <h1 class="text-xl md:text-2xl font-bold text-[#06728A] tracking-tight cursor-default">Rincian Pesanan</h1>
        </div>

        <!-- UX Writing: Success Alert Manual Dismiss -->
        @if (session('success'))
            <div id="success-alert"
                class="mb-6 bg-[#E0F2F7] border border-[#A4D2E1] rounded-2xl p-4 flex items-start gap-3 shadow-sm transition-all duration-500 opacity-100 transform translate-y-0">
                <div
                    class="w-6 h-6 rounded-full bg-[#06728A] text-white flex items-center justify-center shrink-0 mt-0.5 shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h4 class="text-sm font-bold text-[#055c70]">Pesanan Berhasil Dibuat</h4>
                    <p class="text-xs text-[#06728A] mt-0.5 leading-relaxed font-medium">Yay! Pesanan berhasil dibuat. Tim
                        Natuna GAS akan segera mengecek pesananmu.</p>
                </div>
                <button type="button" onclick="closeAlert()"
                    class="text-[#06728A] hover:bg-[#CBE4ED] p-1.5 rounded-lg transition-colors shrink-0 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        @endif

        <!-- LOGIKA STATE TIMELINE -->
        @php
            $isRejected = $order->status === 'rejected';

            // STEP 1: Pesanan Dibuat (Selalu Done)
            $s1 = 'done';

            // STEP 2: Menunggu Konfirmasi / Pembayaran
            $s2 = 'pending';
            if ($isRejected) {
                $s2 = 'rejected';
            } elseif (in_array($order->status, ['pending_approval', 'awaiting_payment'])) {
                $s2 = 'active';
            } elseif (in_array($order->status, ['approved', 'paid', 'shipping', 'completed'])) {
                $s2 = 'done';
            }

            // STEP 3: Sedang Dikirim
            $s3 = 'pending';
            if (!$isRejected) {
                if (in_array($order->status, ['approved', 'paid'])) {
                    $s3 = 'waiting';
                }
                // Pesanan siap dikirim
                elseif ($order->status === 'shipping') {
                    $s3 = 'active';
                } elseif ($order->status === 'completed') {
                    $s3 = 'done';
                }
            }

            // STEP 4: Tiba Di Lokasi
            $s4 = 'pending';
            if (!$isRejected && $order->status === 'completed') {
                $s4 = 'done';
            }
        @endphp

        <!-- Card 1: Status Pengiriman (Visual Stepper sesuai Prototipe) -->
        <div class="bg-slate-50 rounded-3xl p-6 md:p-8 shadow-sm border border-slate-100 mb-6">

            <div class="flex justify-between items-center mb-8 border-b border-slate-200 pb-4">
                <h2 class="text-lg font-bold text-slate-900 cursor-default">Status Pengiriman</h2>
                <span class="text-sm font-bold text-[#06728A] cursor-text">{{ $order->invoice_number }}</span>
            </div>

            <!-- TIMELINE CONTAINER -->
            <div class="pl-2">

                <!-- STEP 1: Pesanan Dibuat -->
                <div class="relative flex gap-5 pb-8">
                    <!-- Vertical Line -->
                    <div
                        class="absolute left-4 top-8 bottom-0 w-0.5 -ml-[1px] {{ in_array($s2, ['active', 'done', 'rejected']) ? 'bg-[#06728A]' : 'bg-slate-200' }}">
                    </div>

                    <!-- Icon -->
                    <div
                        class="relative z-10 w-8 h-8 rounded-full bg-[#06728A] text-white flex items-center justify-center shrink-0 shadow-sm border-2 border-transparent cursor-default">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>

                    <!-- Content -->
                    <div class="pt-1.5 cursor-default">
                        <h4 class="text-sm font-bold text-slate-900">Pesanan Dibuat</h4>
                        <p class="text-[11px] text-slate-500 font-medium mt-0.5">
                            {{ $order->created_at->translatedFormat('d M Y • H:i') }}</p>
                    </div>
                </div>

                <!-- STEP 2: Menunggu Konfirmasi -->
                <div class="relative flex gap-5 pb-8">
                    <!-- Vertical Line -->
                    @if (!$isRejected)
                        <div
                            class="absolute left-4 top-8 bottom-0 w-0.5 -ml-[1px] {{ in_array($s3, ['active', 'done', 'waiting']) ? 'bg-[#06728A]' : 'bg-slate-200' }}">
                        </div>
                    @endif

                    <!-- Icon -->
                    <div
                        class="relative z-10 w-8 h-8 rounded-full flex items-center justify-center shrink-0 cursor-default
                    {{ $s2 === 'done'
                        ? 'bg-[#06728A] text-white border-2 border-transparent shadow-sm'
                        : ($s2 === 'active'
                            ? 'bg-white border-2 border-[#06728A] shadow-sm'
                            : ($s2 === 'rejected'
                                ? 'bg-red-500 text-white border-2 border-transparent'
                                : 'bg-slate-100 text-slate-300 border-2 border-transparent')) }}">

                        @if ($s2 === 'done')
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                            </svg>
                        @elseif($s2 === 'rejected')
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        @elseif($s2 === 'active')
                            <!-- Inner Dot untuk state Active -->
                            <div class="w-2.5 h-2.5 bg-[#06728A] rounded-full"></div>
                        @else
                            <div class="w-2 h-2 bg-slate-300 rounded-full"></div>
                        @endif
                    </div>

                    <!-- Content -->
                    <div class="pt-1.5 cursor-default">
                        <h4
                            class="text-sm font-bold {{ $s2 === 'active' || $s2 === 'done' ? 'text-slate-900' : ($s2 === 'rejected' ? 'text-red-600' : 'text-slate-400') }}">
                            {{ $s2 === 'rejected' ? 'Pesanan Dibatalkan' : 'Menunggu Konfirmasi' }}
                        </h4>

                        @if ($s2 === 'active' || $s2 === 'rejected')
                            <p
                                class="text-[11px] font-medium leading-relaxed mt-1 max-w-[240px] {{ $s2 === 'rejected' ? 'text-red-500' : 'text-slate-500' }}">
                                @if ($isRejected)
                                    {{ $order->rejection_reason ?? 'Dibatalkan oleh sistem/admin.' }}
                                @elseif($order->status === 'awaiting_payment')
                                    Menunggu Anda mengunggah bukti pembayaran.
                                @else
                                    Menunggu verifikasi pembayaran dan ketersediaan stok.
                                @endif
                            </p>
                        @endif

                        <!-- Lencana Admin / Tombol Upload -->
                        @if ($s2 === 'active' && $order->status === 'pending_approval')
                            <div
                                class="mt-3 inline-flex items-center gap-1.5 bg-white border border-slate-200 text-slate-800 px-3 py-1.5 rounded-full shadow-sm select-none">
                                <svg class="w-4 h-4 text-[#06728A]" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-[10px] font-bold tracking-wide">Admin Sedang Meninjau Pesananmu</span>
                            </div>
                        @endif

                        @if ($s2 === 'active' && $order->status === 'awaiting_payment')
                            <div class="mt-3">
                                <a href="{{ route('checkout.payment', $order->id) }}"
                                    class="inline-flex items-center gap-1.5 bg-[#06728A] text-white px-4 py-2 rounded-lg shadow-sm hover:bg-[#055c70] transition-colors text-xs font-bold active:scale-95">
                                    Unggah Bukti Bayar
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- STEP 3: Sedang Dikirim -->
                @if (!$isRejected)
                    <div class="relative flex gap-5 pb-8">
                        <!-- Vertical Line -->
                        <div
                            class="absolute left-4 top-8 bottom-0 w-0.5 -ml-[1px] {{ $s4 === 'done' ? 'bg-[#06728A]' : 'bg-slate-200' }}">
                        </div>

                        <!-- Icon -->
                        <div
                            class="relative z-10 w-8 h-8 rounded-full flex items-center justify-center shrink-0 cursor-default
                    {{ $s3 === 'done'
                        ? 'bg-[#06728A] text-white border-2 border-transparent shadow-sm'
                        : (in_array($s3, ['active', 'waiting'])
                            ? 'bg-white border-2 border-[#06728A] text-[#06728A] shadow-sm'
                            : 'bg-slate-100 text-slate-400 border-2 border-transparent') }}">
                            @if ($s3 === 'done')
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="3"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                                </svg>
                            @else
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                </svg>
                            @endif
                        </div>

                        <!-- Content -->
                        <div class="pt-1.5 cursor-default">
                            <h4
                                class="text-sm font-bold {{ in_array($s3, ['active', 'waiting']) ? 'text-[#06728A]' : ($s3 === 'done' ? 'text-slate-900' : 'text-slate-400') }}">
                                {{ $s3 === 'waiting' ? 'Siap Dikirim' : 'Sedang Dikirim' }}
                            </h4>
                            @if (in_array($s3, ['active', 'waiting']))
                                <p class="text-[11px] font-medium leading-relaxed mt-1 text-slate-500">
                                    {{ $s3 === 'waiting' ? 'Pesanan telah dipacking dan menunggu penjemputan kurir.' : 'Kurir dalam perjalanan menuju lokasi Anda.' }}
                                </p>
                            @endif
                        </div>
                    </div>

                    <!-- STEP 4: Tiba Di Lokasi Pengiriman -->
                    <div class="relative flex gap-5">
                        <!-- Icon -->
                        <div
                            class="relative z-10 w-8 h-8 rounded-full flex items-center justify-center shrink-0 cursor-default
                    {{ $s4 === 'done' ? 'bg-[#06728A] text-white border-2 border-transparent shadow-sm' : 'bg-slate-100 text-slate-400 border-2 border-transparent' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                                </path>
                            </svg>
                        </div>

                        <!-- Content -->
                        <div class="pt-1.5 cursor-default">
                            <h4 class="text-sm font-bold {{ $s4 === 'done' ? 'text-emerald-600' : 'text-slate-400' }}">
                                {{ $s4 === 'done' ? 'Tiba Di Lokasi Pengiriman' : 'Tiba Di Lokasi Pengiriman' }}
                            </h4>
                            @if ($s4 === 'done')
                                <p class="text-[11px] text-slate-500 font-medium mt-1">
                                    {{ $order->updated_at->translatedFormat('d M Y • H:i') }}</p>
                            @endif
                        </div>
                    </div>
                @endif

            </div>

            <!-- Tombol Hubungi Admin (Diletakkan di dalam Card Timeline sesuai Prototipe) -->
            <div class="mt-8 pt-6 border-t border-slate-200">
                <a href="https://wa.me/6281234567890" target="_blank"
                    class="w-full flex items-center justify-center gap-2 py-3.5 bg-white border border-slate-300 text-slate-800 text-sm font-bold rounded-xl hover:bg-slate-50 hover:border-slate-400 transition-colors shadow-sm active:scale-95 cursor-pointer">
                    Hubungi Admin
                </a>
            </div>
        </div>

        <!-- Card 2: Informasi Pembayaran & Pengiriman -->
        <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-slate-100 mb-6 space-y-5">
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1 cursor-default">Metode
                    Pembayaran</p>
                <p class="text-sm font-bold text-slate-800 cursor-text">
                    @if ($order->payment_method === 'transfer')
                        Transfer Bank
                    @elseif($order->payment_method === 'cod')
                        Bayar di Tempat (COD)
                    @else
                        Tempo / Hutang (B2B)
                    @endif
                </p>
            </div>

            <div class="w-full h-px bg-slate-100"></div>

            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1 cursor-default">Alamat
                    Pengiriman</p>
                <p class="text-sm font-medium text-slate-600 leading-relaxed cursor-text">
                    {{ $order->shipping_address ?: 'Diambil di Toko / Sesuai Profil' }}</p>
            </div>
        </div>

        <!-- Card 3: Daftar Produk -->
        <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-slate-100 mb-6">
            <h2 class="text-base font-bold text-slate-900 mb-5 cursor-default">Daftar Produk</h2>

            <div class="space-y-4">
                @foreach ($order->items as $item)
                    <div class="flex gap-4">
                        <div
                            class="w-16 h-16 bg-[#F0F8FA] border border-[#E0F2F7] rounded-2xl overflow-hidden shrink-0 flex items-center justify-center select-none">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($item->product->name) }}&color=06728A&background=CBE4ED"
                                class="w-full h-full object-cover">
                        </div>

                        <div class="flex flex-col justify-center w-full">
                            <h4
                                class="text-xs md:text-sm font-bold text-slate-800 line-clamp-2 leading-snug mb-1 cursor-text">
                                {{ $item->product->name }}</h4>
                            <div class="flex justify-between items-end">
                                <span class="text-[11px] font-bold text-slate-500 cursor-text">
                                    {{ $item->quantity }} x Rp {{ number_format($item->price_at_time, 0, ',', '.') }}
                                </span>
                                <span class="text-sm font-bold text-[#06728A] cursor-text">
                                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>
                    @if (!$loop->last)
                        <div class="w-full h-px bg-slate-50"></div>
                    @endif
                @endforeach
            </div>
        </div>

        <!-- Card 4: Ringkasan Harga -->
        <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-slate-100 mb-6">
            <div class="space-y-3 mb-5">
                <div class="flex justify-between items-center text-sm">
                    <span class="text-slate-500 font-medium cursor-default">Subtotal Produk</span>
                    <span class="font-bold text-slate-800 cursor-text">Rp
                        {{ number_format($order->grand_total, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="flex justify-between items-center bg-[#F0F8FA] p-4 rounded-xl border border-[#CBE4ED]">
                <span class="font-bold text-slate-800 text-sm cursor-default">Total Keseluruhan</span>
                <span class="text-xl md:text-2xl font-black text-[#06728A] cursor-text">
                    Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                </span>
            </div>
        </div>

    </div>

    <!-- Script UI -->
    <script>
        // Fungsi tutup notifikasi (Manual Dismissible)
        function closeAlert() {
            const alertBox = document.getElementById('success-alert');
            if (alertBox) {
                alertBox.classList.remove('opacity-100', 'translate-y-0');
                alertBox.classList.add('opacity-0', '-translate-y-4');
                setTimeout(() => alertBox.remove(), 500);
            }
        }
    </script>
@endsection
