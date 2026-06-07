@extends('admin.layouts.app')

@section('content')

    {{-- Back Button + Header --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.payments.index') }}"
            class="p-2 text-gray-400 hover:text-[#06728A] hover:bg-gray-100 rounded-xl transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
        </a>
        <div>
            <h1 class="text-xl font-bold text-gray-900">Verifikasi Pembayaran</h1>
            <p class="text-xs text-gray-400 font-medium mt-0.5">{{ $payment->order->invoice_number }}</p>
        </div>
    </div>

    {{-- Notifikasi --}}
    @if (session('error'))
        <div class="mb-5 bg-red-50 border border-red-200 rounded-2xl p-4">
            <p class="text-sm font-semibold text-red-800">{{ session('error') }}</p>
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-5 bg-red-50 border border-red-200 rounded-2xl p-4">
            @foreach ($errors->all() as $error)
                <p class="text-sm font-semibold text-red-800">{{ $error }}</p>
            @endforeach
        </div>
    @endif

    {{-- STATUS BADGE: Tampilkan info jika sudah diproses --}}
    @if ($payment->status !== 'pending')
        <div
            class="mb-5 rounded-2xl p-4 flex items-center gap-3
            {{ $payment->status === 'approved' ? 'bg-emerald-50 border border-emerald-200' : 'bg-red-50 border border-red-200' }}">
            <svg class="w-5 h-5 shrink-0 {{ $payment->status === 'approved' ? 'text-emerald-500' : 'text-red-500' }}"
                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                @if ($payment->status === 'approved')
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                @else
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                @endif
            </svg>
            <p class="text-sm font-bold {{ $payment->status === 'approved' ? 'text-emerald-800' : 'text-red-800' }}">
                Pembayaran ini sudah
                <strong>{{ $payment->status === 'approved' ? 'DISETUJUI' : 'DITOLAK' }}</strong>
                pada {{ $payment->updated_at->translatedFormat('d M Y, H:i') }}.
            </p>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- ============================================================ --}}
        {{-- KOLOM KIRI: Bukti Foto + Info Pembayaran                     --}}
        {{-- ============================================================ --}}
        <div class="space-y-5">

            {{-- Bukti Transfer --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h2 class="text-sm font-bold text-gray-800">Bukti Transfer</h2>
                    @if ($payment->payment_proof_path)
                        <a href="{{ Storage::url($payment->payment_proof_path) }}" target="_blank"
                            class="text-xs font-bold text-[#06728A] hover:underline flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                            </svg>
                            Buka Original
                        </a>
                    @endif
                </div>

                @if ($payment->payment_proof_path)
                    {{-- Tampilan foto bukti transfer --}}
                    <div class="p-4">
                        <img src="{{ Storage::url($payment->payment_proof_path) }}"
                            alt="Bukti Transfer {{ $payment->order->invoice_number }}"
                            class="w-full rounded-xl border border-gray-100 object-contain max-h-[500px] bg-gray-50 cursor-zoom-in"
                            onclick="openImageModal(this.src)">
                    </div>
                @else
                    <div class="p-10 text-center">
                        <div class="w-16 h-16 bg-orange-50 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-8 h-8 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                </path>
                            </svg>
                        </div>
                        <p class="text-sm font-bold text-gray-600">Belum ada bukti transfer</p>
                        <p class="text-xs text-gray-400 mt-1">User belum mengunggah foto bukti pembayaran.</p>
                    </div>
                @endif
            </div>

            {{-- Info Ringkas Pembayaran --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 space-y-4">
                <h2 class="text-sm font-bold text-gray-800 mb-1">Detail Pembayaran</h2>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Nominal di Sistem</p>
                        <p class="text-lg font-black text-gray-900">Rp {{ number_format($payment->amount, 0, ',', '.') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Grand Total Order</p>
                        <p class="text-lg font-black text-gray-900">Rp
                            {{ number_format($payment->order->grand_total, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Total Sudah Dibayar</p>
                        <p class="text-sm font-bold text-emerald-600">Rp
                            {{ number_format($totalSudahApproved, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Sisa Tagihan</p>
                        <p class="text-sm font-bold {{ $sisaTagihan > 0 ? 'text-orange-500' : 'text-emerald-600' }}">
                            Rp {{ number_format($sisaTagihan, 0, ',', '.') }}
                        </p>
                    </div>
                </div>

                {{-- Progress Bar Pembayaran --}}
                @php
                    $progressPersen =
                        $payment->order->grand_total > 0
                            ? min(100, ($totalSudahApproved / $payment->order->grand_total) * 100)
                            : 0;
                @endphp
                <div>
                    <div class="flex justify-between text-[10px] font-bold text-gray-400 mb-1.5">
                        <span>Progress Pembayaran</span>
                        <span>{{ number_format($progressPersen, 0) }}%</span>
                    </div>
                    <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full rounded-full transition-all duration-500
                            {{ $progressPersen >= 100 ? 'bg-emerald-500' : 'bg-[#06728A]' }}"
                            style="width: {{ $progressPersen }}%"></div>
                    </div>
                </div>
            </div>

            {{-- Riwayat Cicilan --}}
            @if ($payment->order->payments->count() > 1)
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100">
                        <h2 class="text-sm font-bold text-gray-800">Riwayat Pembayaran</h2>
                    </div>
                    <div class="divide-y divide-gray-50">
                        @foreach ($payment->order->payments as $riwayat)
                            <div
                                class="flex items-center justify-between px-5 py-3
                                {{ $riwayat->id === $payment->id ? 'bg-blue-50/50' : '' }}">
                                <div>
                                    <p class="text-xs font-bold text-gray-700">
                                        {{ $riwayat->created_at->translatedFormat('d M Y, H:i') }}
                                        @if ($riwayat->id === $payment->id)
                                            <span
                                                class="ml-1 text-[9px] font-bold text-blue-600 bg-blue-100 px-1.5 py-0.5 rounded-full">INI</span>
                                        @endif
                                    </p>
                                    <p class="text-[10px] text-gray-400 font-medium mt-0.5">
                                        {{ $riwayat->type === 'full_payment' ? 'Bayar Penuh' : 'Cicilan' }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-bold text-gray-800">
                                        Rp {{ number_format($riwayat->amount, 0, ',', '.') }}
                                    </p>
                                    <span
                                        class="text-[9px] font-bold px-2 py-0.5 rounded-full
                                        {{ $riwayat->status === 'approved'
                                            ? 'bg-emerald-100 text-emerald-700'
                                            : ($riwayat->status === 'rejected'
                                                ? 'bg-red-100 text-red-700'
                                                : 'bg-amber-100 text-amber-700') }}">
                                        {{ strtoupper($riwayat->status) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- ============================================================ --}}
        {{-- KOLOM KANAN: Info Order + Form Aksi Admin                    --}}
        {{-- ============================================================ --}}
        <div class="space-y-5">

            {{-- Info Pelanggan & Order --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 space-y-4">
                <h2 class="text-sm font-bold text-gray-800">Informasi Order</h2>

                <div class="space-y-3">
                    <div class="flex justify-between items-start">
                        <p class="text-xs text-gray-400 font-medium">Pelanggan</p>
                        <div class="text-right">
                            <p class="text-xs font-bold text-gray-800">{{ $payment->order->user->name }}</p>
                            @if ($payment->order->user->shop_name)
                                <p class="text-[10px] text-gray-400">{{ $payment->order->user->shop_name }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="flex justify-between items-start">
                        <p class="text-xs text-gray-400 font-medium">Invoice</p>
                        <p class="text-xs font-bold text-[#06728A]">{{ $payment->order->invoice_number }}</p>
                    </div>
                    <div class="flex justify-between items-start">
                        <p class="text-xs text-gray-400 font-medium">Tanggal Order</p>
                        <p class="text-xs font-bold text-gray-700">
                            {{ $payment->order->created_at->translatedFormat('d M Y') }}</p>
                    </div>
                    <div class="flex justify-between items-start">
                        <p class="text-xs text-gray-400 font-medium">Status Pengiriman</p>
                        <span
                            class="text-[10px] font-bold px-2 py-0.5 rounded-full
                            {{ match ($payment->order->delivery_status) {
                                'processing' => 'bg-blue-100 text-blue-700',
                                'shipping' => 'bg-amber-100 text-amber-700',
                                'delivered' => 'bg-emerald-100 text-emerald-700',
                                'cancelled' => 'bg-red-100 text-red-700',
                                default => 'bg-gray-100 text-gray-600',
                            } }}">
                            {{ strtoupper(str_replace('_', ' ', $payment->order->delivery_status)) }}
                        </span>
                    </div>
                    <div class="flex justify-between items-start">
                        <p class="text-xs text-gray-400 font-medium">Alamat Kirim</p>
                        <p class="text-xs font-medium text-gray-700 text-right max-w-[180px] leading-relaxed">
                            {{ $payment->order->shipping_address }}
                        </p>
                    </div>
                </div>

                {{-- Daftar Item Order --}}
                <div class="pt-3 border-t border-gray-100">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-3">Item Pesanan</p>
                    <div class="space-y-2">
                        @foreach ($payment->order->items as $item)
                            <div class="flex justify-between items-center">
                                <p class="text-xs font-medium text-gray-700 truncate flex-1">
                                    {{ $item->product->name ?? 'Produk tidak ditemukan' }}
                                    <span class="text-gray-400">×{{ $item->quantity }}</span>
                                </p>
                                <p class="text-xs font-bold text-gray-800 shrink-0 ml-2">
                                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{--
    ============================================================
    SNIPPET: FORM CARD "MANAJEMEN PENGIRIMAN"
    FILE TARGET: resources/views/admin/pages/orders/show.blade.php

    INSTRUKSI PENEMPATAN:
    Tempel snippet ini di dalam kolom kanan (div.space-y-5)
    halaman show order Anda — sesudah card "Informasi Order"
    dan sebelum card "Tindakan Admin" (jika ada).

    CATATAN:
    - Variabel $payment  → objek Order yang sudah di-load di controller
    - Variabel $couriers → Collection Courier dari fungsi show() yang sudah dimodifikasi
    - old() dipakai untuk mempertahankan pilihan user jika validasi gagal
    ============================================================
--}}

            {{-- ============================================================ --}}
            {{-- NOTIFIKASI SUCCESS (letakkan di area notifikasi halaman show) --}}
            {{-- Jika halaman show Anda sudah punya blok @if (session('success'))  --}}
            {{-- maka SKIP bagian ini — sudah tercakup otomatis.              --}}
            {{-- ============================================================ --}}
            @if (session('success'))
                <div class="mb-5 bg-emerald-50 border border-emerald-200 rounded-2xl p-4 flex items-center gap-3">
                    <svg class="w-5 h-5 shrink-0 text-emerald-500" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-sm font-bold text-emerald-800">{{ session('success') }}</p>
                </div>
            @endif


            {{-- ============================================================ --}}
            {{-- CARD: MANAJEMEN PENGIRIMAN                                    --}}
            {{-- ============================================================ --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

                {{-- Header Card --}}
                <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-3">
                    <div class="w-8 h-8 bg-[#06728A]/10 rounded-xl flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-[#06728A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3
                                   m-4-4l-4 4m0 0l4 4m-4-4h12"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-gray-800">Manajemen Pengiriman</h2>
                        <p class="text-xs text-gray-400 font-medium mt-0.5">Ubah kurir dan status pengiriman pesanan ini.
                        </p>
                    </div>
                </div>

                {{-- Body Form --}}
                <div class="p-5">
                    <form action="{{ route('admin.orders.updateDelivery', $payment) }}" method="POST"
                        class="space-y-4">
                        @csrf
                        @method('PATCH')

                        {{-- ======================== --}}
                        {{-- SELECT: Kurir            --}}
                        {{-- ======================== --}}
                        <div>
                            <label for="courier_id" class="block text-xs font-bold text-gray-700 mb-2">
                                Kurir Pengiriman
                            </label>
                            <div class="relative">
                                {{--
                        Ikon truk di dalam select — pakai pointer-events-none
                        agar tidak menghalangi klik dropdown
                    --}}
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10l2 1
                                               M13 16H9m4 0h4m0 0l2-1V11a1 1 0 00-.293-.707l-3-3A1 1 0 0015 7h-2v9"></path>
                                    </svg>
                                </div>
                                <select id="courier_id" name="courier_id"
                                    class="w-full pl-9 pr-4 py-3 border-2 rounded-xl text-sm font-medium text-gray-800
                               appearance-none focus:outline-none transition-colors cursor-pointer
                               {{ $errors->has('courier_id')
                                   ? 'border-red-400 bg-red-50 focus:border-red-400'
                                   : 'border-gray-200 bg-white focus:border-[#06728A]' }}">

                                    <option value="" disabled
                                        {{ old('courier_id', $payment->courier_id) === null ? 'selected' : '' }}>
                                        -- Pilih Kurir --
                                    </option>

                                    @foreach ($couriers as $courier)
                                        <option value=""
                                            {{ old('courier_id', $payment->order->courier_id) === null ? 'selected' : '' }}>
                                            {{ $courier->name }}
                                        </option>
                                    @endforeach
                                </select>

                                {{-- Ikon chevron kanan --}}
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3.5">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>

                            {{-- Pesan error validasi --}}
                            @error('courier_id')
                                <p class="mt-1.5 text-xs font-semibold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- ======================== --}}
                        {{-- SELECT: Status Pengiriman --}}
                        {{-- ======================== --}}
                        <div>
                            <label for="delivery_status" class="block text-xs font-bold text-gray-700 mb-2">
                                Status Pengiriman
                            </label>

                            {{--
                    Daftar pilihan status sesuai ENUM database:
                    pending | processing | shipping | delivered | cancelled
                --}}
                            @php
                                $statusOptions = [
                                    'pending' => ['label' => 'Pending', 'color' => 'bg-gray-100 text-gray-600'],
                                    'processing' => ['label' => 'Diproses', 'color' => 'bg-blue-100 text-blue-700'],
                                    'shipping' => ['label' => 'Dalam Kirim', 'color' => 'bg-amber-100 text-amber-700'],
                                    'delivered' => [
                                        'label' => 'Terkirim',
                                        'color' => 'bg-emerald-100 text-emerald-700',
                                    ],
                                    'cancelled' => ['label' => 'Dibatalkan', 'color' => 'bg-red-100 text-red-700'],
                                ];
                                $currentStatus = old('delivery_status', $payment->delivery_status);
                            @endphp

                            {{-- Badge status aktif saat ini --}}
                            <div class="mb-2 flex items-center gap-2">
                                <p class="text-[10px] text-gray-400 font-medium">Saat ini:</p>
                                <span
                                    class="text-[10px] font-bold px-2 py-0.5 rounded-full
                        {{ $statusOptions[$payment->delivery_status]['color'] ?? 'bg-gray-100 text-gray-600' }}">
                                    {{ strtoupper($statusOptions[$payment->delivery_status]['label'] ?? $payment->delivery_status) }}
                                </span>
                            </div>

                            <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2
                                               M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                </div>
                                <select id="delivery_status" name="delivery_status"
                                    class="w-full pl-9 pr-4 py-3 border-2 rounded-xl text-sm font-medium text-gray-800
                               appearance-none focus:outline-none transition-colors cursor-pointer
                               {{ $errors->has('delivery_status')
                                   ? 'border-red-400 bg-red-50 focus:border-red-400'
                                   : 'border-gray-200 bg-white focus:border-[#06728A]' }}">

                                    @foreach ($statusOptions as $value => $meta)
                                        <option value="{{ $value }}"
                                            {{ $currentStatus === $value ? 'selected' : '' }}>
                                            {{ $meta['label'] }}
                                        </option>
                                    @endforeach
                                </select>

                                {{-- Ikon chevron kanan --}}
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3.5">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>

                            {{-- Pesan error validasi --}}
                            @error('delivery_status')
                                <p class="mt-1.5 text-xs font-semibold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- ======================== --}}
                        {{-- TOMBOL SUBMIT            --}}
                        {{-- ======================== --}}
                        <div class="pt-1">
                            <button type="submit"
                                class="w-full py-3 bg-[#06728A] hover:bg-[#055f74] text-white text-sm font-bold
                           rounded-xl transition-all active:scale-95 shadow-sm shadow-[#06728A]/20
                           flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                Simpan Perubahan Pengiriman
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            {{-- ============================================================ --}}
            {{-- END SNIPPET: MANAJEMEN PENGIRIMAN                            --}}
            {{-- ============================================================ --}}

            {{-- ============================================================ --}}
            {{-- FORM AKSI ADMIN (hanya tampil kalau status masih 'pending')  --}}
            {{-- ============================================================ --}}
            @if ($payment->status === 'pending' && $payment->payment_proof_path)
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100">
                        <h2 class="text-sm font-bold text-gray-800">Tindakan Admin</h2>
                        <p class="text-xs text-gray-400 mt-0.5">Periksa bukti foto di sebelah kiri, lalu tentukan
                            keputusan.</p>
                    </div>

                    <div class="p-5 space-y-4">

                        {{-- ============================== --}}
                        {{-- FORM: SETUJUI (ACC)            --}}
                        {{-- ============================== --}}
                        <div class="border border-emerald-200 rounded-2xl overflow-hidden">
                            <button type="button" onclick="toggleAccForm()"
                                class="w-full flex items-center justify-between px-5 py-4 bg-emerald-50 hover:bg-emerald-100 transition-colors text-left cursor-pointer"
                                id="btn-toggle-acc">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-8 h-8 bg-emerald-500 rounded-xl flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-emerald-800">ACC / Setujui Pembayaran</p>
                                        <p class="text-[11px] text-emerald-600 font-medium">Isi nominal sesuai bukti foto
                                        </p>
                                    </div>
                                </div>
                                <svg id="acc-chevron" class="w-4 h-4 text-emerald-600 transition-transform duration-200"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            {{-- Form Input Amount --}}
                            <div id="acc-form" class="hidden px-5 pb-5 pt-4 bg-white">
                                <form action="{{ route('admin.payments.approve', $payment) }}" method="POST"
                                    id="form-approve">
                                    @csrf
                                    @method('PATCH')

                                    <div class="mb-4">
                                        <label class="block text-xs font-bold text-gray-700 mb-2">
                                            Nominal yang Diterima (sesuai bukti foto)
                                        </label>
                                        <div class="relative">
                                            <span
                                                class="absolute left-4 top-1/2 -translate-y-1/2 text-sm font-bold text-gray-400">Rp</span>
                                            <input type="number" name="amount" id="input-amount"
                                                class="w-full pl-10 pr-4 py-3 border-2 border-gray-200 focus:border-emerald-400 rounded-xl text-sm font-bold text-gray-900 focus:outline-none transition-colors"
                                                placeholder="0" min="1000" max="{{ $payment->order->grand_total }}"
                                                value="{{ old('amount', $payment->amount) }}"
                                                oninput="previewLunasStatus(this.value)">
                                        </div>

                                        {{-- Preview apakah akan lunas atau cicilan --}}
                                        <div id="preview-status" class="mt-3 p-3 rounded-xl hidden">
                                            <p id="preview-text" class="text-xs font-bold"></p>
                                        </div>

                                        {{-- Info sisa tagihan --}}
                                        <p class="text-[11px] text-gray-400 font-medium mt-2">
                                            Sisa tagihan user: <strong class="text-gray-700">Rp
                                                {{ number_format($sisaTagihan, 0, ',', '.') }}</strong>
                                        </p>
                                    </div>

                                    <button type="submit"
                                        class="w-full py-3 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-bold rounded-xl transition-all active:scale-95 shadow-sm shadow-emerald-200">
                                        Konfirmasi Persetujuan
                                    </button>
                                </form>
                            </div>
                        </div>

                        {{-- ============================== --}}
                        {{-- TOMBOL: TOLAK                  --}}
                        {{-- ============================== --}}
                        <div class="border border-red-200 rounded-2xl overflow-hidden">
                            <button type="button" onclick="toggleRejectForm()"
                                class="w-full flex items-center justify-between px-5 py-4 bg-red-50 hover:bg-red-100 transition-colors text-left cursor-pointer">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-red-500 rounded-xl flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-red-800">Tolak Bukti Pembayaran</p>
                                        <p class="text-[11px] text-red-500 font-medium">User harus kirim ulang bukti baru
                                        </p>
                                    </div>
                                </div>
                                <svg id="reject-chevron" class="w-4 h-4 text-red-400 transition-transform duration-200"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            {{-- Konfirmasi Tolak --}}
                            <div id="reject-form" class="hidden px-5 pb-5 pt-4 bg-white">
                                <div class="bg-red-50 rounded-xl p-4 mb-4">
                                    <p class="text-sm font-bold text-red-800">Yakin menolak bukti ini?</p>
                                    <p class="text-xs text-red-600 mt-1 font-medium">
                                        Setelah ditolak, user akan diminta mengirimkan bukti transfer baru. Status
                                        pembayaran akan tetap "Belum Lunas".
                                    </p>
                                </div>
                                <form action="{{ route('admin.payments.reject', $payment) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                        class="w-full py-3 bg-red-500 hover:bg-red-600 text-white text-sm font-bold rounded-xl transition-all active:scale-95 shadow-sm shadow-red-200">
                                        Ya, Tolak Bukti Ini
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif ($payment->status === 'pending' && !$payment->payment_proof_path)
                <div class="bg-orange-50 border border-orange-200 rounded-2xl p-5 text-center">
                    <p class="text-sm font-bold text-orange-800">Menunggu User Upload Bukti</p>
                    <p class="text-xs text-orange-600 mt-1 font-medium">
                        User belum mengirimkan foto bukti transfer. Tidak ada aksi yang bisa dilakukan sekarang.
                    </p>
                </div>
            @endif
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- MODAL: Zoom Foto Bukti Transfer                              --}}
    {{-- ============================================================ --}}
    <div id="image-modal" class="fixed inset-0 z-[200] hidden items-center justify-center bg-gray-900/80 backdrop-blur-sm"
        onclick="closeImageModal()">
        <img id="modal-img" src="" alt="Bukti Transfer"
            class="max-w-[90vw] max-h-[90vh] rounded-2xl shadow-2xl object-contain">
    </div>

    <script>
        // --- Toggle: Buka/tutup form ACC ---
        function toggleAccForm() {
            const form = document.getElementById('acc-form');
            const chevron = document.getElementById('acc-chevron');
            const isHidden = form.classList.contains('hidden');

            // Tutup reject form kalau terbuka
            document.getElementById('reject-form').classList.add('hidden');
            document.getElementById('reject-chevron').classList.remove('rotate-180');

            if (isHidden) {
                form.classList.remove('hidden');
                chevron.classList.add('rotate-180');
                document.getElementById('input-amount').focus();
            } else {
                form.classList.add('hidden');
                chevron.classList.remove('rotate-180');
            }
        }

        // --- Toggle: Buka/tutup konfirmasi Tolak ---
        function toggleRejectForm() {
            const form = document.getElementById('reject-form');
            const chevron = document.getElementById('reject-chevron');
            const isHidden = form.classList.contains('hidden');

            // Tutup acc form kalau terbuka
            document.getElementById('acc-form').classList.add('hidden');
            document.getElementById('acc-chevron').classList.remove('rotate-180');

            if (isHidden) {
                form.classList.remove('hidden');
                chevron.classList.add('rotate-180');
            } else {
                form.classList.add('hidden');
                chevron.classList.remove('rotate-180');
            }
        }

        // --- Preview status lunas/cicilan saat admin isi amount ---
        const grandTotal = {{ $payment->order->grand_total }};
        const sudahApproved = {{ $totalSudahApproved }};
        const sisaTagihan = {{ $sisaTagihan }};

        function previewLunasStatus(nilai) {
            const previewBox = document.getElementById('preview-status');
            const previewText = document.getElementById('preview-text');
            const nominal = parseFloat(nilai) || 0;

            if (nominal <= 0) {
                previewBox.classList.add('hidden');
                return;
            }

            const totalSetelahIni = sudahApproved + nominal;
            previewBox.classList.remove('hidden');

            if (totalSetelahIni >= grandTotal) {
                // Akan lunas setelah ini
                previewBox.className = 'mt-3 p-3 rounded-xl bg-emerald-50 border border-emerald-200';
                previewText.className = 'text-xs font-bold text-emerald-700';
                previewText.textContent = '✓ Pesanan akan berstatus LUNAS setelah disetujui.';
            } else {
                // Masih cicilan
                const masihKurang = grandTotal - totalSetelahIni;
                previewBox.className = 'mt-3 p-3 rounded-xl bg-orange-50 border border-orange-200';
                previewText.className = 'text-xs font-bold text-orange-700';
                previewText.textContent =
                    `⚠ Akan menjadi cicilan. Sisa tagihan: Rp ${new Intl.NumberFormat('id-ID').format(masihKurang)}`;
            }
        }

        // --- Modal Zoom Foto ---
        function openImageModal(src) {
            const modal = document.getElementById('image-modal');
            document.getElementById('modal-img').src = src;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeImageModal() {
            const modal = document.getElementById('image-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        // Tutup modal dengan tombol Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeImageModal();
        });
    </script>

@endsection
