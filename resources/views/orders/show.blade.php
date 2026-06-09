@extends('layouts.app')

@section('content')
    <div class="px-4 py-6 md:py-8 max-w-2xl mx-auto min-h-screen relative">

        {{-- Header --}}
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('orders.index') }}"
                class="p-2 -ml-2 text-gray-500 hover:text-[#06728A] hover:bg-gray-100 rounded-full transition-all duration-300 cursor-pointer">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <h1 class="text-xl md:text-2xl font-bold text-[#06728A] tracking-tight cursor-default">Rincian Pesanan</h1>
        </div>

        {{-- Notifikasi --}}
        @if (session('success'))
            <div id="success-alert"
                class="mb-6 bg-[#E0F2F7] border border-[#A4D2E1] rounded-2xl p-4 flex items-start gap-3 shadow-sm transition-all duration-500">
                <div class="w-6 h-6 rounded-full bg-[#06728A] text-white flex items-center justify-center shrink-0 mt-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-bold text-[#055c70]">{{ session('success') }}</p>
                </div>
                <button type="button" onclick="closeAlert()"
                    class="text-[#06728A] hover:bg-[#CBE4ED] p-1.5 rounded-lg transition-colors shrink-0 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-r-xl shadow-sm">
                <p class="text-sm font-medium">{{ session('error') }}</p>
            </div>
        @endif

        {{-- =============================== --}}
        {{-- CARD 1: STATUS PENGIRIMAN       --}}
        {{-- =============================== --}}
        @php
            // Tentukan state tiap step dari delivery_status (kolom baru)
            $ds = $order->delivery_status;

            // Step 1 selalu done
            $s1 = 'done';

            // Step 2: Dikonfirmasi Admin
            $s2 = match (true) {
                $ds === 'cancelled' => 'rejected',
                $ds === 'pending' => 'active',
                in_array($ds, ['processing', 'shipping', 'delivered']) => 'done',
                default => 'pending',
            };

            // Step 3: Sedang Dikirim
            $s3 = match (true) {
                $ds === 'cancelled' => 'pending',
                $ds === 'processing' => 'waiting',
                $ds === 'shipping' => 'active',
                $ds === 'delivered' => 'done',
                default => 'pending',
            };

            // Step 4: Tiba di Lokasi
            $s4 = $ds === 'delivered' ? 'done' : 'pending';

            $isRejected = $ds === 'cancelled';
        @endphp

        <div class="bg-slate-50 rounded-3xl p-6 md:p-8 shadow-sm border border-slate-100 mb-6">
            <div class="flex justify-between items-center mb-8 border-b border-slate-200 pb-4">
                <h2 class="text-lg font-bold text-slate-900 cursor-default">Status Pengiriman</h2>
                <span class="text-sm font-bold text-[#06728A] cursor-text">{{ $order->invoice_number }}</span>
            </div>

            <div class="pl-2">
                {{-- STEP 1: Pesanan Dibuat --}}
                <div class="relative flex gap-5 pb-8">
                    <div
                        class="absolute left-4 top-8 bottom-0 w-0.5 -ml-[1px]
                        {{ in_array($s2, ['active', 'done', 'rejected']) ? 'bg-[#06728A]' : 'bg-slate-200' }}">
                    </div>
                    <div
                        class="relative z-10 w-8 h-8 rounded-full bg-[#06728A] text-white flex items-center justify-center shrink-0 shadow-sm border-2 border-transparent">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <div class="pt-1.5">
                        <h4 class="text-sm font-bold text-slate-900">Pesanan Dibuat</h4>
                        <p class="text-[11px] text-slate-500 font-medium mt-0.5">
                            {{ $order->created_at->translatedFormat('d M Y • H:i') }}</p>
                    </div>
                </div>

                {{-- STEP 2: Konfirmasi Admin --}}
                <div class="relative flex gap-5 pb-8">
                    @if (!$isRejected)
                        <div
                            class="absolute left-4 top-8 bottom-0 w-0.5 -ml-[1px]
                            {{ in_array($s3, ['active', 'done', 'waiting']) ? 'bg-[#06728A]' : 'bg-slate-200' }}">
                        </div>
                    @endif
                    <div
                        class="relative z-10 w-8 h-8 rounded-full flex items-center justify-center shrink-0
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
                        @elseif ($s2 === 'rejected')
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        @elseif ($s2 === 'active')
                            <div class="w-2.5 h-2.5 bg-[#06728A] rounded-full"></div>
                        @else
                            <div class="w-2 h-2 bg-slate-300 rounded-full"></div>
                        @endif
                    </div>
                    <div class="pt-1.5">
                        <h4
                            class="text-sm font-bold
                            {{ $s2 === 'active' ? 'text-[#06728A]' : ($s2 === 'done' ? 'text-slate-900' : ($s2 === 'rejected' ? 'text-red-600' : 'text-slate-400')) }}">
                            {{ $s2 === 'rejected' ? 'Pesanan Dibatalkan' : 'Menunggu Konfirmasi Admin' }}
                        </h4>
                        @if ($s2 === 'active')
                            <p class="text-[11px] font-medium text-slate-500 mt-1">Pesanan Anda sedang kami cek.</p>
                        @elseif ($s2 === 'rejected')
                            <p class="text-[11px] font-medium text-red-500 mt-1">Pesanan ini telah dibatalkan.</p>
                        @endif
                    </div>
                </div>

                @if (!$isRejected)
                    {{-- STEP 3: Sedang Dikirim --}}
                    <div class="relative flex gap-5 pb-8">
                        <div
                            class="absolute left-4 top-8 bottom-0 w-0.5 -ml-[1px]
                            {{ $s4 === 'done' ? 'bg-[#06728A]' : 'bg-slate-200' }}">
                        </div>
                        <div
                            class="relative z-10 w-8 h-8 rounded-full flex items-center justify-center shrink-0
                            {{ $s3 === 'done'
                                ? 'bg-[#06728A] text-white border-2 border-transparent shadow-sm'
                                : ($s3 === 'active'
                                    ? 'bg-white border-2 border-[#06728A] shadow-sm'
                                    : ($s3 === 'waiting'
                                        ? 'bg-amber-100 text-amber-600 border-2 border-amber-300'
                                        : 'bg-slate-100 text-slate-300 border-2 border-transparent')) }}">
                            @if ($s3 === 'done')
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="3"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                                </svg>
                            @elseif ($s3 === 'active')
                                <div class="w-2.5 h-2.5 bg-[#06728A] rounded-full"></div>
                            @else
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                </svg>
                            @endif
                        </div>
                        <div class="pt-1.5">
                            <h4
                                class="text-sm font-bold
                                {{ in_array($s3, ['active', 'waiting']) ? 'text-[#06728A]' : ($s3 === 'done' ? 'text-slate-900' : 'text-slate-400') }}">
                                {{ $s3 === 'waiting' ? 'Siap Dikirim' : 'Sedang Dikirim' }}
                            </h4>
                            @if ($s3 === 'waiting')
                                <p class="text-[11px] font-medium text-slate-500 mt-1">Pesanan sedang dipacking.</p>
                            @elseif ($s3 === 'active')
                                <p class="text-[11px] font-medium text-slate-500 mt-1">Kurir dalam perjalanan ke lokasi
                                    Anda.</p>
                            @endif
                        </div>
                    </div>

                    {{-- STEP 4: Tiba di Lokasi --}}
                    <div class="relative flex gap-5">
                        <div
                            class="relative z-10 w-8 h-8 rounded-full flex items-center justify-center shrink-0
                            {{ $s4 === 'done' ? 'bg-[#06728A] text-white border-2 border-transparent shadow-sm' : 'bg-slate-100 text-slate-400 border-2 border-transparent' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                                </path>
                            </svg>
                        </div>
                        <div class="pt-1.5">
                            <h4 class="text-sm font-bold {{ $s4 === 'done' ? 'text-emerald-600' : 'text-slate-400' }}">
                                Tiba Di Lokasi Pengiriman
                            </h4>
                            @if ($s4 === 'done')
                                <p class="text-[11px] text-slate-500 font-medium mt-1">
                                    {{ $order->updated_at->translatedFormat('d M Y • H:i') }}</p>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <div class="mt-8 pt-6 border-t border-slate-200">
                <a href="https://wa.me/6281234567890" target="_blank"
                    class="w-full flex items-center justify-center gap-2 py-3.5 bg-white border border-slate-300 text-slate-800 text-sm font-bold rounded-xl hover:bg-slate-50 transition-colors shadow-sm active:scale-95">
                    Hubungi Admin
                </a>
            </div>
        </div>

        {{-- ================================================================ --}}
        {{-- CARD REKENING BANK: Tampil jika masih belum lunas & metode transfer --}}
        {{-- ================================================================ --}}
        @php
            $firstPayment = $order->payments->first();
            $isTransfer = $firstPayment?->payment_method === 'transfer';
            $masihBelumLunas = $order->payment_status === 'belum_lunas';
        @endphp

        @if ($masihBelumLunas && $isTransfer)
            @php $banks = \App\Models\BankAccount::where('is_active', true)->get(); @endphp

            @if ($banks->count() > 0)
                @php $defaultBank = $banks->first(); @endphp

                <div
                    class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 md:p-6 mb-6 relative overflow-hidden">

                    {{-- Label pojok kanan atas --}}
                    <div
                        class="absolute top-0 right-0 bg-gray-100 border-b border-l border-gray-200 text-gray-500 text-[9px] font-bold uppercase tracking-widest px-3 py-1.5 rounded-bl-xl cursor-default pointer-events-none select-none">
                        Rekening Tujuan
                    </div>

                    <div class="flex justify-between items-start mb-6 mt-3">
                        <div class="flex items-center gap-4">
                            <div id="active-bank-icon"
                                class="w-12 h-12 bg-blue-50 text-blue-700 rounded-xl flex items-center justify-center font-black text-xl border border-blue-100 italic tracking-tighter cursor-default transition-all select-none">
                                {{ strtoupper(substr($defaultBank->bank_name, 0, 3)) }}
                            </div>
                            <div>
                                <h3 id="active-bank-name"
                                    class="font-bold text-gray-900 text-base cursor-text transition-all">
                                    Bank {{ $defaultBank->bank_name }}</h3>
                                <p class="text-xs text-gray-500 font-medium cursor-text">Transfer Manual / Virtual Account
                                </p>
                            </div>
                        </div>

                        @if ($banks->count() > 1)
                            <button type="button" onclick="openBankModal()"
                                class="flex items-center gap-1.5 text-xs font-bold text-[#06728A] border border-[#06728A]/30 bg-white hover:bg-[#F0F8FA] px-3 py-1.5 rounded-lg transition-all active:scale-95 shadow-sm mt-1 cursor-pointer">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                </svg>
                                Ganti Bank
                            </button>
                        @endif
                    </div>

                    <div class="space-y-4 bg-gray-50/50 p-4 rounded-xl border border-gray-100">
                        <div class="border-b border-gray-200 pb-4 flex justify-between items-end">
                            <div>
                                <p
                                    class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1 cursor-default">
                                    Nomor Rekening</p>
                                <p id="active-bank-acc"
                                    class="text-lg font-bold text-gray-900 tracking-wider font-mono cursor-text transition-all">
                                    {{ $defaultBank->account_number }}</p>
                            </div>
                            <button type="button" id="btn-copy-acc"
                                onclick="copyToClipboard('{{ $defaultBank->account_number }}', this)"
                                class="flex items-center gap-1.5 px-3 py-1.5 bg-white shadow-sm border-2 border-gray-100 text-gray-700 hover:text-[#06728A] hover:border-[#06728A] rounded-lg text-xs font-bold transition-all active:scale-95 cursor-pointer group">
                                <svg class="w-3.5 h-3.5 text-gray-400 group-hover:text-[#06728A] transition-colors"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z">
                                    </path>
                                </svg>
                                <span>Salin</span>
                            </button>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1 cursor-default">
                                Nama Pemilik Rekening</p>
                            <p id="active-bank-owner" class="text-sm font-bold text-gray-900 cursor-text transition-all">
                                {{ $defaultBank->account_name }}</p>
                        </div>
                    </div>
                </div>

                {{-- Modal Ganti Bank (hanya muncul kalau ada > 1 rekening aktif) --}}
                @if ($banks->count() > 1)
                    <div id="bank-modal" class="fixed inset-0 z-[100] hidden items-end sm:items-center justify-center">
                        <div id="bank-backdrop"
                            class="absolute inset-0 bg-gray-900/40 backdrop-blur-sm opacity-0 transition-opacity duration-300"
                            onclick="closeBankModal()"></div>
                        <div id="bank-box"
                            class="relative bg-white rounded-t-3xl sm:rounded-3xl w-full sm:max-w-sm mx-0 sm:mx-4 shadow-2xl
                    opacity-0 translate-y-full sm:translate-y-10 sm:scale-95 transition-all duration-300">
                            <div class="p-6">
                                <div class="flex justify-between items-center mb-5">
                                    <h3 class="text-base font-bold text-gray-900">Pilih Rekening Tujuan</h3>
                                    <button type="button" onclick="closeBankModal()"
                                        class="p-1.5 rounded-lg hover:bg-gray-100 text-gray-400 transition-colors cursor-pointer">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                                <div class="space-y-3">
                                    @foreach ($banks as $bank)
                                        <button type="button" onclick="selectBank({{ $bank->id }})"
                                            class="w-full flex items-center gap-4 p-4 border-2 border-gray-100 rounded-2xl hover:border-[#06728A] hover:bg-[#F0F8FA] transition-all cursor-pointer text-left group">
                                            <div
                                                class="w-10 h-10 bg-blue-50 text-blue-700 rounded-xl flex items-center justify-center font-black text-sm border border-blue-100 italic select-none">
                                                {{ strtoupper(substr($bank->bank_name, 0, 3)) }}
                                            </div>
                                            <div>
                                                <p class="font-bold text-sm text-gray-900 group-hover:text-[#06728A]">
                                                    Bank {{ $bank->bank_name }}</p>
                                                <p class="text-xs text-gray-500 font-mono mt-0.5">
                                                    {{ $bank->account_number }}</p>
                                            </div>
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @else
                {{-- Fallback kalau tidak ada rekening aktif --}}
                <div
                    class="bg-white rounded-2xl shadow-sm border border-orange-200 p-5 mb-6 flex flex-col items-center justify-center text-center">
                    <svg class="w-10 h-10 text-orange-400 mb-2" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                        </path>
                    </svg>
                    <p class="text-sm font-bold text-gray-800">Informasi Rekening Belum Tersedia</p>
                    <p class="text-xs text-gray-500 mt-1">Silakan hubungi admin Natuna GAS untuk info rekening transfer.
                    </p>
                </div>
            @endif
        @endif

        {{-- ================================================================ --}}
        {{-- CARD 2: STATUS PEMBAYARAN + FORM PELUNASAN (FITUR BARU)          --}}
        {{-- ================================================================ --}}
        @php
            // Hitung total yang sudah dibayar (dari accessor di Model Order)
            $totalDibayar = $order->total_paid;
            $sisaTagihan = $order->grand_total - $totalDibayar;

            // Cek apakah ada pembayaran yang masih menunggu verifikasi admin
            $adaPendingPayment = $order->payments()->where('status', 'pending')->exists();
        @endphp

        <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-slate-100 mb-6">
            <h2 class="text-base font-bold text-slate-900 mb-5 cursor-default">Status Pembayaran</h2>

            {{-- Badge Status Lunas / Belum Lunas --}}
            <div
                class="flex items-center justify-between mb-5 p-4 rounded-xl
                {{ $order->payment_status === 'lunas' ? 'bg-emerald-50 border border-emerald-200' : 'bg-orange-50 border border-orange-200' }}">
                <div>
                    <p
                        class="text-xs font-bold uppercase tracking-wider
                        {{ $order->payment_status === 'lunas' ? 'text-emerald-600' : 'text-orange-600' }} mb-0.5">
                        {{ $order->payment_status === 'lunas' ? '✓ Lunas' : '⚠ Belum Lunas' }}
                    </p>
                    @if ($order->payment_status !== 'lunas' || $sisaTagihan > 0)
                        <p class="text-sm font-bold text-slate-700">
                            Sisa: Rp {{ number_format($sisaTagihan, 0, ',', '.') }}
                        </p>
                    @else
                        <p class="text-sm font-bold text-slate-700">Pembayaran telah selesai</p>
                    @endif
                </div>
                <div class="text-right">
                    <p class="text-[10px] text-slate-400 font-medium">Terbayar</p>
                    <p class="text-sm font-bold text-slate-700">
                        Rp {{ number_format($totalDibayar, 0, ',', '.') }}
                    </p>
                </div>
            </div>

            {{-- Riwayat Pembayaran --}}
            @if ($order->payments->count() > 0)
                <div class="space-y-3 mb-5">
                    @foreach ($order->payments as $payment)
                        <div class="flex justify-between items-center p-3 rounded-xl bg-slate-50 border border-slate-100">
                            <div>
                                <p class="text-xs font-bold text-slate-700">
                                    {{ $payment->type === 'full_payment' ? 'Pembayaran Penuh' : 'Cicilan/Pelunasan' }}
                                    <span class="font-medium text-slate-500">
                                        · {{ $payment->payment_method === 'transfer' ? 'Transfer' : 'COD' }}
                                    </span>
                                </p>
                                <p class="text-[11px] text-slate-400 mt-0.5">
                                    {{ $payment->created_at->translatedFormat('d M Y • H:i') }}
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold text-slate-800">
                                    Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                </p>
                                {{-- Badge status per payment --}}
                                <span
                                    class="text-[10px] font-bold px-2 py-0.5 rounded-full
                                    {{ $payment->status === 'approved'
                                        ? 'bg-emerald-100 text-emerald-700'
                                        : ($payment->status === 'rejected'
                                            ? 'bg-red-100 text-red-700'
                                            : 'bg-orange-100 text-orange-700') }}">
                                    {{ $payment->status === 'approved' ? 'Terverifikasi' : ($payment->status === 'rejected' ? 'Ditolak' : 'Menunggu Verifikasi') }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- FORM PELUNASAN: Tampil kalau belum lunas dan tidak ada payment pending --}}
            @if ($order->payment_status !== 'lunas' && !$adaPendingPayment && $order->delivery_status !== 'cancelled')
                <div class="border-t border-slate-100 pt-5">
                    <h3 class="text-sm font-bold text-slate-800 mb-1">Upload Bukti Pelunasan</h3>
                    <p class="text-xs text-slate-400 mb-4">
                        Sisa tagihan: <strong class="text-slate-700">Rp
                            {{ number_format($sisaTagihan, 0, ',', '.') }}</strong>.
                        Upload bukti transfer untuk melunasi pesanan.
                    </p>

                    <form action="{{ route('orders.pay', $order->invoice_number) }}" method="POST"
                        enctype="multipart/form-data" id="pelunasan-form">
                        @csrf
                        @if ($errors->any())
                            <div class="mb-3 bg-red-50 border border-red-200 text-red-700 p-3 rounded-xl text-xs">
                                @foreach ($errors->all() as $error)
                                    <p>{{ $error }}</p>
                                @endforeach
                            </div>
                        @endif

                        <label for="payment_proof_pelunasan"
                            class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-slate-200 rounded-xl cursor-pointer hover:border-[#06728A] hover:bg-[#F0F8FA] transition-all group">
                            <svg class="w-8 h-8 text-slate-300 group-hover:text-[#06728A] transition-colors mb-2"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                            <p class="text-xs text-slate-400 group-hover:text-[#06728A]">Klik untuk pilih foto bukti
                                transfer</p>
                            <p id="pelunasan-filename" class="text-xs font-bold text-[#06728A] mt-1 hidden"></p>
                            <input type="file" id="payment_proof_pelunasan" name="payment_proof" class="hidden"
                                accept="image/jpeg,image/png,image/jpg" onchange="showFileName(this)">
                        </label>

                        <button type="submit" id="btn-pelunasan"
                            class="mt-4 w-full py-3.5 bg-[#06728A] hover:bg-[#055c70] text-white text-sm font-bold rounded-xl shadow-md shadow-[#06728A]/20 active:scale-95 transition-all cursor-pointer">
                            Kirim Bukti Pelunasan
                        </button>
                    </form>
                </div>
            @elseif ($adaPendingPayment && $order->payment_status !== 'lunas')
                {{-- Sudah upload, menunggu verifikasi admin --}}
                <div class="border-t border-slate-100 pt-5">
                    <div class="flex items-start gap-3 p-4 bg-orange-50 rounded-xl border border-orange-200">
                        <svg class="w-5 h-5 text-orange-500 shrink-0 mt-0.5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-sm text-orange-800 font-medium">
                            Bukti pembayaran sudah dikirim. Menunggu verifikasi dari Admin Natuna GAS.
                        </p>
                    </div>
                </div>
            @endif
        </div>

        {{-- ================================================================ --}}
        {{-- CARD 3: INFORMASI METODE PEMBAYARAN & ALAMAT                     --}}
        {{-- ================================================================ --}}
        <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-slate-100 mb-6 space-y-5">
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Metode Pembayaran</p>
                <p class="text-sm font-bold text-slate-800">
                    {{-- Ambil dari payment pertama, karena kolom payment_method ada di order_payments --}}
                    @php $firstPayment = $order->payments->first(); @endphp
                    @if ($firstPayment?->payment_method === 'transfer')
                        Transfer Bank
                    @elseif ($firstPayment?->payment_method === 'cod')
                        Bayar di Tempat (COD)
                    @else
                        -
                    @endif
                </p>
            </div>
            <div class="w-full h-px bg-slate-100"></div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Alamat Pengiriman</p>
                <p class="text-sm font-medium text-slate-600 leading-relaxed">
                    {{ $order->shipping_address ?: 'Diambil di Toko' }}</p>
            </div>
        </div>

        {{-- CARD 4: Daftar Produk --}}
        <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-slate-100 mb-6">
            <h2 class="text-base font-bold text-slate-900 mb-5">Daftar Produk</h2>
            <div class="space-y-4">
                @foreach ($order->items as $item)
                    <div class="flex gap-4">
                        <div
                            class="w-16 h-16 bg-[#F0F8FA] border border-[#E0F2F7] rounded-2xl overflow-hidden shrink-0 flex items-center justify-center">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($item->product->name) }}&color=06728A&background=CBE4ED"
                                class="w-full h-full object-cover">
                        </div>
                        <div class="flex flex-col justify-center w-full">
                            <h4 class="text-xs md:text-sm font-bold text-slate-800 line-clamp-2 leading-snug mb-1">
                                {{ $item->product->name }}</h4>
                            <div class="flex justify-between items-end">
                                <span class="text-[11px] font-bold text-slate-500">
                                    {{ $item->quantity }} x Rp {{ number_format($item->price_at_time, 0, ',', '.') }}
                                </span>
                                <span class="text-sm font-bold text-[#06728A]">
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

        {{-- CARD 5: Ringkasan Harga --}}
        <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-slate-100 mb-6">
            <div class="flex justify-between items-center bg-[#F0F8FA] p-4 rounded-xl border border-[#CBE4ED]">
                <span class="font-bold text-slate-800 text-sm">Total Keseluruhan</span>
                <span class="text-xl md:text-2xl font-black text-[#06728A]">
                    Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                </span>
            </div>
        </div>

        {{-- Toast notifikasi salin nomor rekening --}}
        <div id="toast"
            class="fixed bottom-10 left-1/2 transform -translate-x-1/2 bg-gray-900 text-white px-5 py-3 rounded-full text-sm font-medium opacity-0 transition-opacity duration-300 pointer-events-none z-50 shadow-xl flex items-center gap-2">
            <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            Teks berhasil disalin!
        </div>
    </div>

    <script>
        function closeAlert() {
            const alertBox = document.getElementById('success-alert');
            if (alertBox) {
                alertBox.style.opacity = '0';
                setTimeout(() => alertBox.remove(), 500);
            }
        }

        // Tampilkan nama file yang dipilih user
        function showFileName(input) {
            const label = document.getElementById('pelunasan-filename');
            if (input.files && input.files[0]) {
                label.textContent = input.files[0].name;
                label.classList.remove('hidden');
            }
        }

        // Cegah double submit form pelunasan
        const pelunasanForm = document.getElementById('pelunasan-form');
        if (pelunasanForm) {
            pelunasanForm.addEventListener('submit', function() {
                const btn = document.getElementById('btn-pelunasan');
                btn.disabled = true;
                btn.classList.add('opacity-75', 'cursor-wait');
                btn.innerHTML =
                    `<svg class="animate-spin h-5 w-5 text-white inline" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Mengirim...`;
            });
        }

        // --- Fungsi untuk card rekening bank di show.blade ---
        const banksData = @json(\App\Models\BankAccount::where('is_active', true)->get());

        function openBankModal() {
            const modal = document.getElementById('bank-modal');
            const backdrop = document.getElementById('bank-backdrop');
            const box = document.getElementById('bank-box');
            if (!modal) return;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            setTimeout(() => {
                backdrop.classList.remove('opacity-0');
                backdrop.classList.add('opacity-100');
                box.classList.remove('opacity-0', 'translate-y-full', 'sm:translate-y-10', 'sm:scale-95');
                box.classList.add('opacity-100', 'translate-y-0', 'sm:scale-100');
            }, 10);
        }

        function closeBankModal() {
            const modal = document.getElementById('bank-modal');
            const backdrop = document.getElementById('bank-backdrop');
            const box = document.getElementById('bank-box');
            if (!modal) return;
            backdrop.classList.remove('opacity-100');
            backdrop.classList.add('opacity-0');
            box.classList.remove('opacity-100', 'translate-y-0', 'sm:scale-100');
            box.classList.add('opacity-0', 'translate-y-full', 'sm:translate-y-10', 'sm:scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }, 300);
        }

        function selectBank(bankId) {
            const bank = banksData.find(b => b.id === bankId);
            if (!bank) return;
            document.getElementById('active-bank-icon').textContent = bank.bank_name.substring(0, 3).toUpperCase();
            document.getElementById('active-bank-name').textContent = 'Bank ' + bank.bank_name;
            document.getElementById('active-bank-acc').textContent = bank.account_number;
            document.getElementById('active-bank-owner').textContent = bank.account_name;
            document.getElementById('btn-copy-acc').setAttribute('onclick',
                `copyToClipboard('${bank.account_number}', this)`);
            closeBankModal();
        }

        function copyToClipboard(text, btnElement) {
            navigator.clipboard.writeText(text).then(() => {
                const originalHtml = btnElement.innerHTML;
                btnElement.innerHTML =
                    `<svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> <span>Disalin</span>`;

                const toast = document.getElementById('toast');
                if (toast) {
                    toast.classList.remove('opacity-0');
                    toast.classList.add('opacity-100');
                }

                setTimeout(() => {
                    btnElement.innerHTML = originalHtml;
                    if (toast) {
                        toast.classList.remove('opacity-100');
                        toast.classList.add('opacity-0');
                    }
                }, 2000);
            }).catch(() => alert('Gagal menyalin teks.'));
        }
    </script>
@endsection
