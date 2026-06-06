@extends('layouts.app')

@section('content')
    <div class="px-4 py-6 md:py-8 max-w-7xl mx-auto min-h-screen relative pb-36 md:pb-10">

        <div class="flex items-center gap-3 mb-6 md:mb-8">
            <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('home') }}"
                class="p-2 -ml-2 text-gray-500 hover:text-[#06728A] hover:bg-gray-100 rounded-full transition-all duration-300 cursor-pointer"
                title="Kembali">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <h1 class="text-lg md:text-xl font-bold text-gray-800 tracking-tight cursor-default md:hidden">
                Detail Produk
            </h1>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-12 gap-8 lg:gap-12">

            <div class="md:col-span-5 lg:col-span-4">
                <div class="sticky top-24">
                    <div
                        class="aspect-square w-full bg-[#F0F8FA] rounded-2xl md:rounded-3xl border border-gray-100 overflow-hidden shadow-sm flex items-center justify-center">
                        <img src="{{ $product->image_path ? asset('storage/' . $product->image_path) : 'https://ui-avatars.com/api/?name=' . urlencode($product->name) . '&color=06728A&background=CBE4ED&size=500' }}"
                            alt="{{ $product->name }}"
                            class="w-full h-full object-cover object-center hover:scale-105 transition-transform duration-500">
                    </div>
                </div>
            </div>

            <div class="md:col-span-7 lg:col-span-8 flex flex-col">

                <span class="text-xs font-bold text-[#06728A] uppercase tracking-wider mb-2 cursor-default">
                    {{ $product->category->name ?? 'Kebutuhan Pokok' }}
                </span>

                <h1 class="text-2xl md:text-3xl lg:text-4xl font-bold text-gray-900 leading-tight mb-4 cursor-text">
                    {{ $product->name }}
                </h1>

                <div class="flex items-end gap-2 mb-6">
                    <span class="text-3xl md:text-4xl lg:text-5xl font-black text-[#06728A] tracking-tight">
                        Rp {{ number_format($product->base_price, 0, ',', '.') }}
                    </span>
                    <span class="text-sm md:text-base text-gray-500 font-medium mb-1 md:mb-1.5">/
                        {{ $product->unit ?? 'dus' }}</span>
                </div>

                <div class="flex items-center gap-2 mb-8">
                    @if ($product->stock > $product->low_stock_threshold)
                        <span
                            class="flex items-center gap-1.5 bg-emerald-50 text-emerald-700 px-3 py-1 rounded-full text-xs font-bold border border-emerald-100">
                            <div class="w-2 h-2 rounded-full bg-emerald-500"></div> Stok Tersedia: {{ $product->stock }}
                        </span>
                    @elseif($product->stock > 0)
                        <span
                            class="flex items-center gap-1.5 bg-orange-50 text-orange-700 px-3 py-1 rounded-full text-xs font-bold border border-orange-100">
                            <div class="w-2 h-2 rounded-full bg-orange-505"></div> Sisa Stok: {{ $product->stock }}
                            (Menipis)
                        </span>
                    @else
                        <span
                            class="flex items-center gap-1.5 bg-red-50 text-red-700 px-3 py-1 rounded-full text-xs font-bold border border-red-100">
                            <div class="w-2 h-2 rounded-full bg-red-500"></div> Stok Habis
                        </span>
                    @endif
                </div>

                <div class="hidden md:block bg-white border border-gray-100 shadow-sm rounded-2xl p-6 mb-8">
                    <div class="flex items-end gap-6">

                        <div class="flex-shrink-0">
                            <label class="block text-sm font-bold text-gray-700 mb-3">Jumlah Pesanan</label>
                            <div class="flex items-center border-2 border-gray-100 rounded-xl bg-gray-50 w-36">
                                <button type="button" onclick="decrementQty('qty-desktop')"
                                    class="w-10 h-11 flex items-center justify-center text-gray-500 hover:text-[#06728A] hover:bg-gray-200/50 rounded-l-xl transition-colors cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="3"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4"></path>
                                    </svg>
                                </button>
                                <input type="number" id="qty-desktop" value="1" min="1"
                                    max="{{ $product->stock }}"
                                    class="w-full h-11 bg-transparent text-center font-bold text-gray-900 border-none focus:ring-0 p-0 appearance-none pointer-events-none">
                                <button type="button" onclick="incrementQty('qty-desktop', {{ $product->stock }})"
                                    class="w-10 h-11 flex items-center justify-center text-gray-500 hover:text-[#06728A] hover:bg-gray-200/50 rounded-r-xl transition-colors cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="3"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="flex-1 flex gap-3">
                            <button type="button" onclick="submitToCart('add')"
                                @if ($product->stock < 1) disabled @endif
                                class="flex-1 border-2 border-[#06728A] text-[#06728A] hover:bg-[#F0F8FA] disabled:opacity-50 disabled:cursor-not-allowed rounded-xl py-3 text-sm font-bold transition-all active:scale-95 cursor-pointer flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                                    </path>
                                </svg>
                                + Keranjang
                            </button>

                            <button type="button" onclick="submitToCart('direct')"
                                @if ($product->stock < 1) disabled @endif
                                class="flex-1 bg-gradient-to-r from-[#06728A] to-[#0891b2] hover:opacity-90 disabled:opacity-50 disabled:cursor-not-allowed text-white rounded-xl py-3 text-sm font-bold transition-all shadow-md active:scale-95 cursor-pointer flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                                Beli Sekarang
                            </button>
                        </div>

                    </div>
                </div>

                <div class="bg-white rounded-2xl md:rounded-3xl p-5 md:p-6 lg:p-8 shadow-sm border border-gray-100 flex-1">
                    <h2 class="text-base font-bold text-gray-900 mb-4 flex items-center gap-2 cursor-default">
                        <svg class="w-5 h-5 text-[#06728A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h7"></path>
                        </svg>
                        Deskripsi Produk
                    </h2>
                    <div
                        class="prose prose-sm md:prose-base prose-gray max-w-none text-gray-600 leading-relaxed cursor-text">
                        {!! nl2br(e($product->description)) !!}
                    </div>
                </div>
            </div>
        </div>

        @if (isset($relatedProducts) && $relatedProducts->count() > 0)
            <div class="mt-12 md:mt-20">
                <h2 class="text-xl md:text-2xl font-bold text-gray-900 mb-6 cursor-default">Rekomendasi Lainnya</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
                    @foreach ($relatedProducts as $related)
                        <a href="{{ route('products.show', $related) }}"
                            class="bg-white rounded-2xl p-3 md:p-4 shadow-sm border border-gray-100 hover:border-[#06728A]/30 hover:shadow-md transition-all group cursor-pointer block">
                            <div class="aspect-square bg-gray-50 rounded-xl mb-3 overflow-hidden">
                                <img src="{{ $related->image_path ? asset('storage/' . $related->image_path) : 'https://ui-avatars.com/api/?name=' . urlencode($related->name) . '&color=06728A&background=CBE4ED' }}"
                                    alt="{{ $related->name }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                            </div>
                            <h3
                                class="text-xs md:text-sm font-bold text-gray-800 line-clamp-2 mb-1 group-hover:text-[#06728A] transition-colors">
                                {{ $related->name }}</h3>
                            <p class="text-sm md:text-base font-black text-[#06728A]">Rp
                                {{ number_format($related->base_price, 0, ',', '.') }}</p>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

    </div>

    <div
        class="md:hidden fixed bottom-[70px] left-0 w-full bg-white border-t border-gray-200 shadow-[0_-8px_20px_-10px_rgba(0,0,0,0.1)] z-[60] p-4 flex items-center justify-between gap-4 transition-all">

        <div class="flex items-center border-2 border-gray-100 rounded-xl bg-gray-50 w-28 shrink-0 h-11">
            <button type="button" onclick="decrementQty('qty-mobile')"
                class="w-8 h-full flex items-center justify-center text-gray-500 active:bg-gray-200 rounded-l-xl">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4"></path>
                </svg>
            </button>
            <input type="number" id="qty-mobile" value="1" min="1" max="{{ $product->stock }}"
                class="w-full h-full bg-transparent text-center font-bold text-gray-900 border-none focus:ring-0 p-0 appearance-none pointer-events-none">
            <button type="button" onclick="incrementQty('qty-mobile', {{ $product->stock }})"
                class="w-8 h-full flex items-center justify-center text-gray-500 active:bg-gray-200 rounded-r-xl">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
                </svg>
            </button>
        </div>

        <div class="flex-1 flex gap-2 h-11">
            <button type="button" onclick="submitToCart('add')" @if ($product->stock < 1) disabled @endif
                class="flex-1 border border-[#06728A] text-[#06728A] active:bg-gray-50 disabled:opacity-50 rounded-xl text-xs font-bold transition-transform active:scale-95">
                + Keranjang
            </button>
            <button type="button" onclick="submitToCart('direct')" @if ($product->stock < 1) disabled @endif
                class="flex-1 bg-gradient-to-r from-[#06728A] to-[#0891b2] text-white rounded-xl text-xs font-bold shadow-sm transition-transform active:scale-95">
                Beli Langsung
            </button>
        </div>
    </div>

    <div id="toast-notification"
        class="fixed top-5 md:top-10 left-1/2 transform -translate-x-1/2 flex items-center w-max p-4 space-x-3 text-white bg-[#06728A] rounded-full shadow-xl opacity-0 pointer-events-none transition-all duration-500 z-[100] translate-y-[-20px]"
        role="alert">
        <div class="inline-flex items-center justify-center flex-shrink-0 w-6 h-6 bg-white/20 rounded-full">
            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
        <div class="text-sm font-semibold tracking-wide pr-2" id="toast-message">Produk berhasil ditambahkan.</div>
    </div>

    <script>
        // Menyembunyikan panah input bawaan browser
        const style = document.createElement('style');
        style.innerHTML = `
        input[type=number]::-webkit-inner-spin-button, 
        input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
    `;
        document.head.appendChild(style);

        function incrementQty(inputId, maxStock) {
            let input = document.getElementById(inputId);
            let currentValue = parseInt(input.value) || 1;
            if (currentValue < maxStock) {
                input.value = currentValue + 1;
            }
        }

        function decrementQty(inputId) {
            let input = document.getElementById(inputId);
            let currentValue = parseInt(input.value) || 1;
            if (currentValue > 1) {
                input.value = currentValue - 1;
            }
        }

        // Fungsi Pengiriman AJAX Cerdas (Menangani 'add' ke keranjang dan 'direct' checkout)
        function submitToCart(actionType) {
            const isMobile = window.innerWidth < 768;
            const qtyInputId = isMobile ? 'qty-mobile' : 'qty-desktop';
            const quantity = parseInt(document.getElementById(qtyInputId).value) || 1;
            const productId = "{{ $product->id }}";

            let targetUrl = actionType === 'direct' ? "{{ route('cart.directCheckout') }}" : "{{ route('cart.add') }}";

            fetch(targetUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        quantity: quantity
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        if (response.status === 401) {
                            window.location.href = "{{ route('login') }}";
                            return;
                        }
                        throw new Error('Gagal memproses permintaan.');
                    }
                    return response.json();
                })
                .then(data => {
                    if (actionType === 'direct' && data.success) {
                        // Jalur Beli Sekarang: Langsung redirect ke URL checkout pilihan pembayaran
                        window.location.href = data.redirect_url;
                    } else if (data.status === 'success') {
                        // Jalur Tambah Keranjang Biasa: Tampilkan Toast Notifikasi
                        showToast(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kendala jaringan. Silakan coba lagi.');
                });
        }

        function showToast(message) {
            const toast = document.getElementById('toast-notification');
            document.getElementById('toast-message').textContent = message;

            toast.classList.remove('opacity-0', 'pointer-events-none', 'translate-y-[-20px]');
            toast.classList.add('opacity-100', 'translate-y-0');

            setTimeout(() => {
                toast.classList.remove('opacity-100', 'translate-y-0');
                toast.classList.add('opacity-0', 'pointer-events-none', 'translate-y-[-20px]');
            }, 2500);
        }
    </script>
@endsection
