@extends('layouts.app')

@section('content')
    <div class="px-4 py-6 md:py-8 max-w-3xl mx-auto min-h-screen relative">

        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('orders.index') }}"
                class="p-2 -ml-2 text-gray-500 hover:text-[#06728A] hover:bg-gray-100 rounded-full transition-all duration-300 cursor-pointer">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <h1 class="text-xl md:text-2xl font-bold text-[#06728A] tracking-tight cursor-default">Rincian Pesanan</h1>
        </div>

        @if (session('success'))
            <div id="success-alert"
                class="mb-6 bg-[#E6F4EA] border border-[#A5D6A7] rounded-2xl p-4 flex items-start gap-3 shadow-sm transition-all duration-500 opacity-100 transform translate-y-0">
                <div
                    class="w-6 h-6 rounded-full bg-emerald-500 text-white flex items-center justify-center shrink-0 mt-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h4 class="text-sm font-bold text-emerald-800">Pesanan Berhasil Dibuat</h4>
                    <p class="text-xs text-emerald-700 mt-0.5 leading-relaxed font-medium">Yay! Pesanan berhasil dibuat. Tim
                        Natuna GAS akan segera mengecek pesananmu.</p>
                </div>
                <button type="button" onclick="closeAlert()"
                    class="text-emerald-600 hover:bg-emerald-100 p-1.5 rounded-lg transition-colors shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        @endif

        @php
            $statusMap = [
                'pending_approval' => [
                    'text' => 'Menunggu Konfirmasi Admin',
                    'desc' => 'Tim kami sedang meninjau pesanan dan ketersediaan stok Anda.',
                    'color' => 'text-amber-700',
                    'bg' => 'bg-amber-50',
                    'border' => 'border-amber-200',
                    'icon' =>
                        '<path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>',
                ],
                'awaiting_payment' => [
                    'text' => 'Menunggu Pembayaran',
                    'desc' => 'Silakan unggah bukti transfer agar pesanan dapat diproses.',
                    'color' => 'text-amber-700',
                    'bg' => 'bg-amber-50',
                    'border' => 'border-amber-200',
                    'icon' =>
                        '<path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>',
                ],
                'approved' => [
                    'text' => 'Pesanan Disetujui',
                    'desc' => 'Pesanan Anda telah disetujui dan sedang disiapkan.',
                    'color' => 'text-blue-700',
                    'bg' => 'bg-blue-50',
                    'border' => 'border-blue-200',
                    'icon' =>
                        '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>',
                ],
                'paid' => [
                    'text' => 'Pembayaran Diterima',
                    'desc' => 'Pembayaran valid. Barang segera masuk antrean pengiriman.',
                    'color' => 'text-emerald-700',
                    'bg' => 'bg-emerald-50',
                    'border' => 'border-emerald-200',
                    'icon' =>
                        '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>',
                ],
                'shipping' => [
                    'text' => 'Sedang Dikirim',
                    'desc' => 'Kurir kami sedang menuju ke lokasi pengiriman Anda.',
                    'color' => 'text-[#06728A]',
                    'bg' => 'bg-[#E0F2F7]',
                    'border' => 'border-[#A4D2E1]',
                    'icon' =>
                        '<path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>',
                ],
                'completed' => [
                    'text' => 'Pesanan Selesai',
                    'desc' => 'Pesanan telah diterima. Terima kasih telah berbelanja di Natuna GAS.',
                    'color' => 'text-emerald-700',
                    'bg' => 'bg-emerald-50',
                    'border' => 'border-emerald-200',
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>',
                ],
                'rejected' => [
                    'text' => 'Pesanan Dibatalkan',
                    'desc' => $order->rejection_reason ?? 'Pesanan ini tidak dapat diproses lebih lanjut oleh sistem.',
                    'color' => 'text-red-700',
                    'bg' => 'bg-red-50',
                    'border' => 'border-red-200',
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>',
                ],
            ];

            $paymentMap = [
                'transfer' => 'Transfer Bank',
                'cod' => 'Bayar di Tempat (COD)',
                'hutang' => 'Tempo / Hutang (B2B)',
            ];

            $ui = $statusMap[$order->status] ?? $statusMap['pending_approval'];
            $paymentMethod = $paymentMap[$order->payment_method] ?? 'Metode Lainnya';
        @endphp

        <div class="bg-white rounded-2xl p-5 md:p-6 shadow-sm border border-gray-100 mb-6">
            <div class="flex justify-between items-start mb-4">
                <h2 class="text-base font-bold text-gray-900">Status Pesanan</h2>
                <span class="text-sm font-bold text-[#06728A]">{{ $order->invoice_number }}</span>
            </div>

            <div class="{{ $ui['bg'] }} {{ $ui['border'] }} border rounded-xl p-4 flex gap-4 items-start">
                <div
                    class="w-10 h-10 rounded-full bg-white flex items-center justify-center shrink-0 shadow-sm {{ $ui['color'] }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5"
                        viewBox="0 0 24 24">{!! $ui['icon'] !!}</svg>
                </div>
                <div>
                    <h3 class="font-bold text-sm mb-1 {{ $ui['color'] }}">{{ $ui['text'] }}</h3>
                    <p class="text-xs text-gray-600 font-medium leading-relaxed">{{ $ui['desc'] }}</p>
                </div>
            </div>

            @if ($order->status === 'awaiting_payment')
                <div class="mt-4">
                    <a href="{{ route('checkout.payment', $order->id) }}"
                        class="block w-full text-center bg-[#06728A] text-white rounded-xl py-3 text-sm font-bold hover:bg-[#055c70] transition-colors shadow-sm active:scale-[0.98]">
                        Unggah Bukti Pembayaran Sekarang
                    </a>
                </div>
            @endif
        </div>

        <div class="bg-white rounded-2xl p-5 md:p-6 shadow-sm border border-gray-100 mb-6 space-y-5">

            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Tanggal Pesanan</p>
                <p class="text-sm font-bold text-gray-800">{{ $order->created_at->translatedFormat('d F Y • H:i') }} WIB</p>
            </div>

            <div class="w-full h-px bg-gray-100"></div>

            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Metode Pembayaran</p>
                <p class="text-sm font-bold text-gray-800">{{ $paymentMethod }}</p>
            </div>

            <div class="w-full h-px bg-gray-100"></div>

            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Alamat Pengiriman</p>
                <p class="text-sm font-medium text-gray-600 leading-relaxed">
                    {{ $order->shipping_address ?: 'Diambil di Toko / Sesuai Profil' }}</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 md:p-6 shadow-sm border border-gray-100 mb-6">
            <h2 class="text-base font-bold text-gray-900 mb-4">Daftar Produk</h2>

            <div class="space-y-4">
                @foreach ($order->items as $item)
                    <div class="flex gap-4">
                        <div
                            class="w-16 h-16 bg-[#F0F8FA] border border-[#E0F2F7] rounded-xl overflow-hidden shrink-0 flex items-center justify-center">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($item->product->name) }}&color=06728A&background=CBE4ED"
                                class="w-full h-full object-cover">
                        </div>

                        <div class="flex flex-col justify-center w-full">
                            <h4 class="text-xs md:text-sm font-bold text-gray-800 line-clamp-2 leading-snug mb-1">
                                {{ $item->product->name }}</h4>
                            <div class="flex justify-between items-end">
                                <span class="text-[11px] font-bold text-gray-500">
                                    {{ $item->quantity }} x Rp {{ number_format($item->price_at_time, 0, ',', '.') }}
                                </span>
                                <span class="text-sm font-bold text-[#06728A]">
                                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>
                    @if (!$loop->last)
                        <div class="w-full h-px bg-gray-50"></div>
                    @endif
                @endforeach
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 md:p-6 shadow-sm border border-gray-100 mb-8">
            <div class="space-y-3 mb-4">
                <div class="flex justify-between items-center text-sm">
                    <span class="text-gray-500 font-medium">Subtotal Produk</span>
                    <span class="font-bold text-gray-800">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="flex justify-between items-center bg-[#F0F8FA] p-4 rounded-xl border border-[#E0F2F7]">
                <span class="font-bold text-gray-800 text-sm">Total Keseluruhan</span>
                <span class="text-xl md:text-2xl font-black text-[#06728A]">
                    Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                </span>
            </div>
        </div>

        <a href="https://wa.me/6281234567890" target="_blank"
            class="w-full flex items-center justify-center gap-2 py-4 bg-white border-2 border-gray-200 text-gray-700 font-bold rounded-xl hover:bg-gray-50 hover:border-gray-300 transition-colors active:scale-95 shadow-sm">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                </path>
            </svg>
            Hubungi Admin Natuna
        </a>

    </div>

    <script>
        function closeAlert() {
            const alertBox = document.getElementById('success-alert');
            if (alertBox) {
                // Menambahkan animasi fade-out sebelum dihapus
                alertBox.classList.remove('opacity-100', 'translate-y-0');
                alertBox.classList.add('opacity-0', '-translate-y-4');

                // Tunggu animasi selesai (500ms) baru hapus elemen
                setTimeout(() => alertBox.remove(), 500);
            }
        }
    </script>
@endsection
