@extends('layouts.app')

@section('content')
    <div class="px-4 py-6 md:py-8 md:px-0 max-w-7xl mx-auto min-h-screen">

        <div class="flex items-center gap-3 mb-6 md:mb-8">
            <button type="button" id="btn-back"
                class="p-2 -ml-2 text-gray-500 hover:text-[#06728A] hover:bg-[#F0F8FA] rounded-full transition-all duration-300 cursor-pointer">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </button>
            <h1 class="text-2xl font-bold text-[#06728A] tracking-tight cursor-default">Checkout</h1>
        </div>

        @if (session('error') || $errors->any())
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-r-xl shadow-sm">
                <p class="font-bold text-sm">Terjadi Kesalahan:</p>
                <ul class="list-disc list-inside text-xs mt-1">
                    @if (session('error'))
                        <li>{{ session('error') }}</li>
                    @endif
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-start">

            <div class="lg:col-span-5 order-1 lg:order-2">
                <div class="bg-gray-50/50 rounded-3xl p-5 md:p-6 border border-gray-100 sticky top-10 shadow-sm">
                    <h2 class="text-lg font-bold text-gray-900 mb-5 cursor-default">Ringkasan Pesanan</h2>

                    <div class="space-y-4 max-h-[300px] lg:max-h-[400px] overflow-y-auto pr-2 custom-scrollbar">
                        @foreach ($cart->items as $item)
                            <div class="flex gap-4 p-3 bg-white rounded-2xl shadow-sm border border-gray-50">
                                <div
                                    class="w-16 h-16 bg-[#F0F8FA] rounded-xl overflow-hidden shrink-0 flex items-center justify-center">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($item->product->name) }}&color=06728A&background=CBE4ED"
                                        class="w-full h-full object-cover">
                                </div>
                                <div class="flex flex-col justify-center w-full">
                                    <div class="flex justify-between items-start">
                                        <h4
                                            class="text-xs font-bold text-gray-800 line-clamp-2 pr-2 leading-snug cursor-pointer">
                                            {{ $item->product->name }}</h4>
                                        <span class="text-xs font-bold text-gray-500 shrink-0">x{{ $item->quantity }}</span>
                                    </div>
                                    <div class="mt-1 text-sm font-bold text-[#06728A] cursor-text">
                                        Rp {{ number_format($item->quantity * $item->product->base_price, 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="my-5 border-t border-dashed border-gray-300"></div>

                    <div class="space-y-2 mb-4">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-500 font-medium">Subtotal ({{ $cart->items->count() }} Produk)</span>
                            <span class="font-bold text-gray-800 cursor-text">
                                Rp
                                {{ number_format($cart->items->sum(fn($i) => $i->quantity * $i->product->base_price), 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    <div class="flex justify-between items-center bg-[#E0F2F7] p-4 rounded-xl border border-[#CBE4ED]">
                        <span class="font-bold text-gray-800 text-sm">Total Keseluruhan</span>
                        <span class="text-xl font-bold text-[#06728A] cursor-text">
                            Rp
                            {{ number_format($cart->items->sum(fn($i) => $i->quantity * $i->product->base_price), 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-7 order-2 lg:order-1 space-y-6">
                <form action="{{ route('checkout.process') }}" method="POST" id="checkout-form">
                    @csrf

                    <div class="bg-white rounded-2xl p-5 md:p-6 shadow-sm border border-gray-100 mb-6">
                        <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2 cursor-default">
                            <svg class="w-5 h-5 text-[#06728A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.243-4.243a8 8 0 1111.314 0z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Alamat Pengiriman
                        </h2>

                        <div class="space-y-3">
                            <label class="relative cursor-pointer block">
                                <input type="radio" name="address_type" value="registered" class="peer sr-only"
                                    onchange="toggleAddressOption()"
                                    {{ old('address_type', 'registered') === 'registered' ? 'checked' : '' }}>
                                <div
                                    class="flex items-center justify-between p-4 bg-white border-2 border-gray-100 rounded-xl hover:bg-gray-50 peer-checked:border-[#06728A] peer-checked:bg-[#F0F8FA] peer-checked:[&_.outer-circle]:border-[#06728A] peer-checked:[&_.inner-circle]:scale-100 peer-checked:[&_.icon-addr]:text-[#06728A] transition-all duration-300">
                                    <div class="flex items-start gap-3 pr-4">
                                        <svg class="icon-addr w-5 h-5 text-gray-400 mt-0.5 transition-colors" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                                            </path>
                                        </svg>
                                        <div class="flex flex-col">
                                            <span class="font-bold text-sm text-gray-800">Alamat Terdaftar</span>
                                            <span
                                                class="text-xs text-gray-500 mt-1 leading-relaxed cursor-text">{{ auth()->user()->address ?? 'Alamat belum diatur' }}</span>
                                        </div>
                                    </div>
                                    <div
                                        class="outer-circle w-5 h-5 rounded-full border-2 border-gray-300 flex items-center justify-center transition-colors shrink-0">
                                        <div
                                            class="inner-circle w-2.5 h-2.5 rounded-full bg-[#06728A] scale-0 transition-transform">
                                        </div>
                                    </div>
                                </div>
                            </label>

                            <label class="relative cursor-pointer block">
                                <input type="radio" name="address_type" value="new" class="peer sr-only"
                                    onchange="toggleAddressOption()" {{ old('address_type') === 'new' ? 'checked' : '' }}>
                                <div
                                    class="flex items-center justify-between p-4 bg-white border-2 border-gray-100 rounded-xl hover:bg-gray-50 peer-checked:border-[#06728A] peer-checked:bg-[#F0F8FA] peer-checked:[&_.outer-circle]:border-[#06728A] peer-checked:[&_.inner-circle]:scale-100 peer-checked:[&_.icon-addr]:text-[#06728A] transition-all duration-300">
                                    <div class="flex items-center gap-3">
                                        <svg class="icon-addr w-5 h-5 text-gray-400 transition-colors" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                        <span class="font-bold text-sm text-gray-800">Gunakan Alamat Baru</span>
                                    </div>
                                    <div
                                        class="outer-circle w-5 h-5 rounded-full border-2 border-gray-300 flex items-center justify-center transition-colors shrink-0">
                                        <div
                                            class="inner-circle w-2.5 h-2.5 rounded-full bg-[#06728A] scale-0 transition-transform">
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>

                        <div id="address-input-wrapper"
                            class="transition-all duration-500 ease-in-out overflow-hidden max-h-0 opacity-0">
                            <textarea name="shipping_address" id="shipping_address" rows="3" required
                                class="w-full bg-gray-50 border border-gray-200 focus:border-[#06728A] focus:ring-4 focus:ring-[#06728A]/10 rounded-xl p-4 text-sm font-medium text-gray-800 placeholder-gray-400 outline-none transition-all duration-300 resize-none cursor-text mt-4"
                                placeholder="Ketik alamat lengkap pengiriman baru disini...">{{ old('shipping_address', auth()->user()->address) }}</textarea>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl p-5 md:p-6 shadow-sm border border-gray-100 mb-6">
                        <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2 cursor-default">
                            <svg class="w-5 h-5 text-[#06728A]" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                                </path>
                            </svg>
                            Metode Pembayaran
                        </h2>

                        <div class="space-y-3">
                            <label class="relative cursor-pointer block">
                                <input type="radio" name="payment_method" value="transfer" class="peer sr-only"
                                    {{ old('payment_method', 'transfer') === 'transfer' ? 'checked' : '' }}>
                                <div
                                    class="flex items-center justify-between p-4 bg-white border-2 border-gray-100 rounded-xl hover:bg-gray-50 peer-checked:border-[#06728A] peer-checked:bg-[#F0F8FA] peer-checked:[&_.outer-circle]:border-[#06728A] peer-checked:[&_.inner-circle]:scale-100 peer-checked:[&_.icon-method]:text-[#06728A] transition-all duration-300">
                                    <div class="flex items-center gap-3">
                                        <svg class="icon-method w-6 h-6 text-gray-400 transition-colors" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                        </svg>
                                        <span class="font-bold text-sm text-gray-800">Transfer Bank</span>
                                    </div>
                                    <div
                                        class="outer-circle w-5 h-5 rounded-full border-2 border-gray-300 flex items-center justify-center transition-colors">
                                        <div
                                            class="inner-circle w-2.5 h-2.5 rounded-full bg-[#06728A] scale-0 transition-transform">
                                        </div>
                                    </div>
                                </div>
                            </label>

                            <label class="relative cursor-pointer block">
                                <input type="radio" name="payment_method" value="cod" class="peer sr-only"
                                    {{ old('payment_method') === 'cod' ? 'checked' : '' }}>
                                <div
                                    class="flex flex-col p-4 bg-white border-2 border-gray-100 rounded-xl hover:bg-gray-50 peer-checked:border-[#06728A] peer-checked:bg-[#F0F8FA] peer-checked:[&_.outer-circle]:border-[#06728A] peer-checked:[&_.inner-circle]:scale-100 peer-checked:[&_.icon-method]:text-[#06728A] peer-checked:[&_.cod-info]:flex transition-all duration-300">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <svg class="icon-method w-6 h-6 text-gray-400 transition-colors"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z">
                                                </path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0">
                                                </path>
                                            </svg>
                                            <span class="font-bold text-sm text-gray-800">COD (Bayar ke Kurir)</span>
                                        </div>
                                        <div
                                            class="outer-circle w-5 h-5 rounded-full border-2 border-gray-300 flex items-center justify-center transition-colors">
                                            <div
                                                class="inner-circle w-2.5 h-2.5 rounded-full bg-[#06728A] scale-0 transition-transform">
                                            </div>
                                        </div>
                                    </div>
                                    <div
                                        class="cod-info hidden mt-3 bg-red-50 text-red-600 text-[11px] p-2.5 rounded-lg items-start gap-2">
                                        <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <p class="leading-tight cursor-text">Pastikan uang tunai tersedia saat kurir tiba.
                                            Pesanan tidak dapat dibatalkan.</p>
                                    </div>
                                </div>
                            </label>
                            
                        </div>
                    </div>

                    <div class="hidden md:block">
                        <button type="submit" id="btn-submit-desktop"
                            class="w-full bg-[#06728A] hover:bg-[#055c70] text-white rounded-2xl py-4 text-sm font-bold tracking-wide flex items-center justify-center gap-2 transition-all shadow-md active:scale-[0.98] cursor-pointer">
                            BUAT PESANAN
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </button>
                    </div>

                </form>
            </div>

        </div>
    </div>

    <div
        class="md:hidden fixed bottom-0 left-0 w-full bg-white border-t border-gray-100 shadow-[0_-10px_15px_-3px_rgba(0,0,0,0.05)] z-40 p-4 pb-6">
        <div class="flex justify-between items-end mb-3">
            <span class="text-sm font-bold text-gray-800">Total Tagihan</span>
            <span class="text-xl font-bold text-[#06728A] cursor-text">
                Rp {{ number_format($cart->items->sum(fn($i) => $i->quantity * $i->product->base_price), 0, ',', '.') }}
            </span>
        </div>
        <button type="button" id="btn-submit-mobile" onclick="document.getElementById('checkout-form').requestSubmit();"
            class="w-full bg-[#06728A] hover:bg-[#055c70] text-white rounded-2xl py-3.5 text-sm font-bold flex items-center justify-center gap-2 shadow-md active:scale-95 transition-all cursor-pointer">
            BUAT PESANAN <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3">
                </path>
            </svg>
        </button>
    </div>

    <div id="cancel-modal" class="fixed inset-0 z-[100] hidden items-center justify-center">
        <div class="absolute inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity opacity-0 duration-300"
            id="cancel-backdrop"></div>
        <div class="relative bg-white rounded-2xl p-6 max-w-[320px] w-full mx-4 shadow-2xl transform scale-95 opacity-0 transition-all duration-300"
            id="cancel-box">
            <div class="w-12 h-12 rounded-full bg-amber-100 flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                    </path>
                </svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-2 cursor-default">Tinggalkan halaman checkout?</h3>
            <p class="text-sm text-gray-500 mb-6 cursor-default">Informasi yang sudah Anda isi belum disimpan. Jika keluar
                sekarang, data tersebut akan hilang.</p>
            <div class="flex gap-3 justify-end">
                <button type="button" id="btn-keep-checkout"
                    class="px-4 py-2 text-sm font-bold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors cursor-pointer">Lanjutkan
                    Checkout</button>
                <button type="button" id="btn-confirm-cancel"
                    class="px-4 py-2 text-sm font-bold text-white bg-red-500 hover:bg-red-600 rounded-xl shadow-md shadow-red-500/20 active:scale-95 transition-all cursor-pointer">Keluar</button>
            </div>
        </div>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
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

    <script>
        const databaseAddress = `{!! addslashes(auth()->user()->address ?? '') !!}`;

        // 1. Logika Toggle Pilihan Alamat
        function toggleAddressOption() {
            const addressType = document.querySelector('input[name="address_type"]:checked').value;
            const wrapper = document.getElementById('address-input-wrapper');
            const textarea = document.getElementById('shipping_address');

            if (addressType === 'registered') {
                textarea.value = databaseAddress;
                wrapper.classList.remove('max-h-[300px]', 'opacity-100', 'mt-4');
                wrapper.classList.add('max-h-0', 'opacity-0');
            } else {
                if (textarea.value === databaseAddress) {
                    textarea.value = '';
                }
                wrapper.classList.remove('max-h-0', 'opacity-0');
                wrapper.classList.add('max-h-[300px]', 'opacity-100', 'mt-4');
                textarea.focus();
            }
        }

        // --- LOGIKA MODAL BATAL CHECKOUT ---
        const cancelModal = document.getElementById('cancel-modal');
        const cancelBackdrop = document.getElementById('cancel-backdrop');
        const cancelBox = document.getElementById('cancel-box');
        const btnBack = document.getElementById('btn-back');

        function showCancelModal() {
            cancelModal.classList.remove('hidden');
            cancelModal.classList.add('flex');
            setTimeout(() => {
                cancelBackdrop.classList.remove('opacity-0');
                cancelBackdrop.classList.add('opacity-100');
                cancelBox.classList.remove('opacity-0', 'scale-95');
                cancelBox.classList.add('opacity-100', 'scale-100');
            }, 10);
        }

        function hideCancelModal() {
            cancelBackdrop.classList.remove('opacity-100');
            cancelBackdrop.classList.add('opacity-0');
            cancelBox.classList.remove('opacity-100', 'scale-100');
            cancelBox.classList.add('opacity-0', 'scale-95');
            setTimeout(() => {
                cancelModal.classList.add('hidden');
                cancelModal.classList.remove('flex');
            }, 300);
        }

        if (btnBack) {
            btnBack.addEventListener('click', showCancelModal);
        }
        document.getElementById('btn-keep-checkout').addEventListener('click', hideCancelModal);
        cancelBackdrop.addEventListener('click', hideCancelModal);
        document.getElementById('btn-confirm-cancel').addEventListener('click', function() {
            window.location.href = "{{ route('cart.index') }}";
        });

        // Inisialisasi awal saat dokumen selesai dimuat
        document.addEventListener('DOMContentLoaded', function() {
            toggleAddressOption();
        });

        // Pencegahan Double Submit
        document.getElementById('checkout-form').addEventListener('submit', function(e) {
            const btnDesktop = document.getElementById('btn-submit-desktop');
            const btnMobile = document.getElementById('btn-submit-mobile');
            const loadingSvg =
                `<svg class="animate-spin h-5 w-5 text-white inline" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memproses...`;

            if (btnDesktop) {
                btnDesktop.disabled = true;
                btnDesktop.classList.add('opacity-75', 'cursor-not-allowed');
                btnDesktop.innerHTML = loadingSvg;
            }
            if (btnMobile) {
                btnMobile.disabled = true;
                btnMobile.classList.add('opacity-75', 'cursor-not-allowed');
                btnMobile.innerHTML = loadingSvg;
            }
        });
    </script>
@endsection
