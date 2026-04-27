@extends('layouts.app')

@section('content')
    <div class="px-4 py-6 md:py-8 md:px-0 max-w-3xl mx-auto relative min-h-screen flex flex-col" id="cart-container">

        <div class="flex items-center justify-between mb-6">
            <a href="{{ route('home') }}" class="p-2 -ml-2 text-gray-400 hover:text-[#06728A] transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                    </path>
                </svg>
            </a>
            <h1 class="text-xl font-bold text-[#06728A] tracking-tight">Keranjang Belanja</h1>
            <div class="w-8"></div>
        </div>

        @if ($cart && $cart->items->count() > 0)
            <div class="flex-grow space-y-4 mb-32" id="cart-items-wrapper">
                @foreach ($cart->items as $item)
                    <div class="cart-item bg-white rounded-2xl p-4 flex gap-4 shadow-sm border border-gray-50 hover:shadow-md transition-all duration-300 relative group"
                        data-id="{{ $item->id }}" data-price="{{ $item->product->base_price }}">

                        <button
                            class="btn-remove absolute top-3 right-3 text-gray-300 hover:text-red-500 hover:bg-red-50 p-1.5 rounded-full transition-colors cursor-pointer z-10">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>

                        <div
                            class="w-20 h-20 bg-gray-100 rounded-xl overflow-hidden shrink-0 flex items-center justify-center">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($item->product->name) }}&color=06728A&background=CBE4ED"
                                class="w-full h-full object-cover">
                        </div>

                        <div class="flex flex-col flex-grow justify-between">
                            <div class="pr-6">
                                <h3 class="text-sm font-semibold text-gray-800 leading-snug">{{ $item->product->name }}</h3>
                                <p class="text-[11px] text-gray-500 mt-0.5 font-medium">Rp
                                    {{ number_format($item->product->base_price, 0, ',', '.') }} / unit</p>
                            </div>

                            <div class="flex items-center justify-between mt-3">
                                <div class="flex items-center bg-gray-50 border border-gray-100 rounded-full p-1">
                                    <button
                                        class="btn-decrease w-8 h-8 flex items-center justify-center text-gray-500 hover:text-[#06728A] hover:bg-white rounded-full transition-all shadow-sm active:scale-95 cursor-pointer disabled:opacity-50">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M20 12H4"></path>
                                        </svg>
                                    </button>
                                    <span
                                        class="item-qty w-8 text-center text-sm font-bold text-gray-800">{{ $item->quantity }}</span>
                                    <button
                                        class="btn-increase w-8 h-8 flex items-center justify-center text-gray-500 hover:text-[#06728A] hover:bg-white rounded-full transition-all shadow-sm active:scale-95 cursor-pointer">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4v16m8-8H4"></path>
                                        </svg>
                                    </button>
                                </div>

                                <span class="item-subtotal text-sm font-bold text-gray-900">
                                    Rp {{ number_format($item->quantity * $item->product->base_price, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div
                class="fixed bottom-[60px] md:bottom-0 left-0 w-full bg-white border-t border-gray-100 shadow-[0_-10px_15px_-3px_rgba(0,0,0,0.05)] z-40">
                <div class="max-w-3xl mx-auto px-4 py-4 md:py-5">
                    <div class="flex justify-between items-center mb-3 text-sm">
                        <span class="text-gray-500 font-medium" id="summary-count">Subtotal ({{ $cart->items->count() }}
                            Produk)</span>
                        <span class="font-bold text-gray-800" id="summary-subtotal">Rp
                            {{ number_format($cart->items->sum(fn($i) => $i->quantity * $i->product->base_price), 0, ',', '.') }}</span>
                    </div>

                    <div class="flex justify-between items-end mb-4">
                        <span class="text-base font-bold text-gray-800">Total Keseluruhan</span>
                        <span class="text-xl md:text-2xl font-bold text-[#06728A] tracking-tight" id="summary-total">
                            Rp
                            {{ number_format($cart->items->sum(fn($i) => $i->quantity * $i->product->base_price), 0, ',', '.') }}
                        </span>
                    </div>

                    <button id="btn-checkout"
                        class="w-full bg-[#06728A] hover:bg-[#055c70] text-white rounded-2xl py-3.5 text-sm font-bold tracking-wide flex items-center justify-center gap-2 transition-all shadow-md active:scale-[0.98] cursor-pointer">
                        <span>BUAT PESANAN</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </button>
                </div>
            </div>
        @else
            <div class="flex flex-col items-center justify-center flex-grow pt-20">
                <div class="w-32 h-32 bg-[#F0F8FA] rounded-full flex items-center justify-center mb-6">
                    <svg class="w-16 h-16 text-[#A4D2E1]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-gray-800 mb-2">Keranjang Kosong</h2>
                <p class="text-gray-500 mb-8 text-center text-sm">Ayo mulai belanja kebutuhan sembako dengan harga terbaik.
                </p>
                <a href="{{ route('home') }}"
                    class="px-8 py-3 bg-[#06728A] text-white font-bold rounded-full shadow-md hover:bg-[#055c70] transition-colors">Belanja
                    Sekarang</a>
            </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Formatter Rupiah
            const formatIDR = (number) => new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(number);

            // Update UI Wrapper (Optimistic UI)
            const updateSummaryUI = (data) => {
                document.getElementById('summary-subtotal').textContent = formatIDR(data.subtotal);
                document.getElementById('summary-total').textContent = formatIDR(data.total);
                document.getElementById('summary-count').textContent = `Subtotal (${data.item_count} Produk)`;

                if (data.item_count === 0) {
                    location.reload(); // Reload untuk memunculkan Empty State jika keranjang habis
                }
            };

            // Fungsi Fetch API Modular
            const handleCartAction = async (itemId, actionUrl, method, cardElement) => {
                // Visual Feedback (Loading state)
                cardElement.classList.add('opacity-50', 'pointer-events-none');

                try {
                    const response = await fetch(actionUrl, {
                        method: method,
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        }
                    });

                    const data = await response.json();

                    if (response.ok) {
                        if (method === 'DELETE') {
                            cardElement.style.display = 'none'; // Animasi hilang instan
                            cardElement.remove();
                        }
                        updateSummaryUI(data);
                    } else {
                        alert(data.message || 'Gagal memproses data.');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Koneksi terputus. Silakan coba lagi.');
                } finally {
                    cardElement.classList.remove('opacity-50', 'pointer-events-none');
                }
            };

            // Event Delegation untuk Qty & Delete (Efisien & Clean Code)
            const cartContainer = document.getElementById('cart-items-wrapper');
            if (cartContainer) {
                cartContainer.addEventListener('click', (e) => {
                    const card = e.target.closest('.cart-item');
                    if (!card) return;

                    const itemId = card.dataset.id;
                    const price = parseFloat(card.dataset.price);
                    const qtyElement = card.querySelector('.item-qty');
                    const subtotalElement = card.querySelector('.item-subtotal');
                    let currentQty = parseInt(qtyElement.textContent);

                    // Increase Qty
                    if (e.target.closest('.btn-increase')) {
                        currentQty++;
                        qtyElement.textContent = currentQty;
                        subtotalElement.textContent = formatIDR(currentQty * price);
                        handleCartAction(itemId, `/keranjang/${itemId}`, 'PATCH', card);
                    }

                    // Decrease Qty
                    if (e.target.closest('.btn-decrease') && currentQty > 1) {
                        currentQty--;
                        qtyElement.textContent = currentQty;
                        subtotalElement.textContent = formatIDR(currentQty * price);
                        handleCartAction(itemId, `/keranjang/${itemId}?action=decrease`, 'PATCH',
                        card); // Parameter untuk backend
                    }

                    // Remove Item
                    if (e.target.closest('.btn-remove')) {
                        if (confirm('Hapus produk ini dari keranjang?')) {
                            handleCartAction(itemId, `/keranjang/${itemId}`, 'DELETE', card);
                        }
                    }
                });
            }

            // Handle Checkout Process
            const btnCheckout = document.getElementById('btn-checkout');
            if (btnCheckout) {
                btnCheckout.addEventListener('click', async () => {
                    // Visual Feedback Checkout
                    btnCheckout.classList.add('opacity-75', 'cursor-wait');
                    btnCheckout.innerHTML =
                        `<svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memproses...`;

                    try {
                        const response = await fetch('{{ route('checkout.process') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            }
                        });

                        const data = await response.json();

                        if (response.ok && data.success) {
                            window.location.href = data.redirect_url;
                        } else {
                            alert(data.message);
                            location.reload();
                        }
                    } catch (error) {
                        alert('Terjadi kesalahan. Silakan coba lagi.');
                        location.reload();
                    }
                });
            }
        });
    </script>
@endsection
