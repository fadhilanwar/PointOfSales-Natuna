@extends('layouts.app')

@section('content')
    <div class="px-4 py-6 md:py-8 max-w-7xl mx-auto min-h-screen relative pb-36 md:pb-10">

        <div
            class="mb-6 md:mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-gray-100 pb-6">

            <div class="flex flex-col">
                <div class="flex items-center gap-2 mb-1">
                    <a href="{{ route('home') }}"
                        class="p-2 -ml-2 text-gray-500 hover:text-[#06728A] hover:bg-gray-100 rounded-full transition-all duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                    </a>
                    <h1 class="text-xl md:text-2xl font-medium text-gray-700 tracking-tight">
                        Hasil pencarian untuk: <span class="font-black text-gray-900">"{{ $query }}"</span>
                    </h1>
                </div>
                <p class="text-sm text-gray-500 font-medium ml-10">
                    Ditemukan {{ $products->total() }} produk grosir.
                </p>
            </div>

            <form action="{{ route('products.search') }}" method="GET"
                class="relative w-full md:w-80 lg:w-96 group mt-2 md:mt-0">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400 group-focus-within:text-[#06728A] transition-colors" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="search" name="q" value="{{ $query }}" required
                    class="w-full bg-white hover:bg-gray-50 border border-gray-200 focus:border-[#06728A] rounded-full py-3 pl-11 pr-20 text-sm font-medium focus:outline-none focus:ring-4 focus:ring-[#06728A]/10 shadow-sm transition-all"
                    placeholder="Cari produk lain...">
                <button type="submit"
                    class="absolute inset-y-1.5 right-1.5 px-4 bg-[#06728A] hover:bg-[#055c70] text-white rounded-full text-xs font-bold transition-all shadow-sm active:scale-95">
                    Cari
                </button>
            </form>
        </div>

        @if ($products->isEmpty())
            <div
                class="flex flex-col items-center justify-center py-16 md:py-24 px-4 text-center bg-white rounded-3xl border border-dashed border-gray-200 shadow-sm mt-4">
                <div
                    class="w-24 h-24 md:w-32 md:h-32 bg-[#F0F8FA] rounded-full flex items-center justify-center mb-6 shadow-inner animate-pulse">
                    <svg class="w-12 h-12 md:w-16 md:h-16 text-[#A4D2E1]" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>

                <h2 class="text-xl md:text-2xl font-bold text-gray-900 mb-2 tracking-tight">Oops, produk tidak ditemukan!
                </h2>
                <p class="text-gray-500 max-w-md mx-auto mb-8 text-sm md:text-base leading-relaxed">
                    Kami tidak dapat menemukan produk yang cocok dengan kata kunci <span
                        class="font-bold text-gray-700">"{{ $query }}"</span>. Coba gunakan kata kunci yang lebih
                    umum seperti "Minyak" atau "Beras".
                </p>

                <a href="{{ route('home') }}"
                    class="inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-gradient-to-r from-[#06728A] to-[#0891b2] text-white font-bold rounded-full shadow-md hover:shadow-lg active:scale-95 transition-all">
                    Kembali ke Beranda
                </a>
            </div>
        @else
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-3 md:gap-6 mb-10 items-start">
                @foreach ($products as $product)
                    <div
                        class="product-card bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden flex flex-col hover:shadow-lg hover:shadow-[#06728A]/5 hover:-translate-y-1 hover:border-[#CBE4ED] transition-all duration-300 ease-in-out group relative cursor-default">

                        <a href="{{ route('products.show', $product) }}"
                            class="block aspect-w-1 aspect-h-1 w-full bg-gray-50 relative overflow-hidden cursor-pointer">
                            <img src="{{ $product->image_path ? asset('storage/' . $product->image_path) : 'https://ui-avatars.com/api/?name=' . urlencode($product->name) . '&color=06728A&background=CBE4ED' }}"
                                alt="{{ $product->name }}"
                                class="w-full h-36 md:h-44 object-cover object-center group-hover:scale-105 transition-transform duration-500 ease-in-out cursor-pointer">
                        </a>

                        <div class="p-3 md:p-4 flex flex-col flex-grow">
                            <h4
                                class="text-[13px] md:text-sm font-bold text-gray-900 line-clamp-2 leading-snug h-[38px] md:h-[40px] group-hover:text-[#06728A] transition-colors duration-300 cursor-pointer">
                                <a href="{{ route('products.show', $product) }}" class="hover:underline outline-none">
                                    {{ $product->name }}
                                </a>
                            </h4>

                            <div class="mt-2 mb-2">
                                <div class="text-base md:text-xl font-bold text-[#06728A] tracking-tight cursor-text">
                                    Rp {{ number_format($product->base_price, 0, ',', '.') }}
                                </div>
                                <div class="text-[11px] md:text-[13px] font-medium text-gray-600 mt-0.5 cursor-text">
                                    / {{ $product->unit ?? 'dus' }}
                                </div>
                            </div>

                            <div class="h-px bg-gray-200 w-full mb-4 mt-1"></div>

                            <div class="mt-auto relative h-[42px] md:h-[46px]">

                                <button type="button"
                                    class="btn-trigger absolute inset-0 w-full bg-[#06728A] text-white hover:bg-[#055c70] rounded-full text-xs md:text-sm font-bold flex items-center justify-center gap-2 shadow-sm transition-all duration-300 z-10 cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                                        </path>
                                    </svg>
                                    Tambah
                                </button>

                                <div
                                    class="qty-controls absolute inset-0 w-full flex items-center justify-between gap-1.5 md:gap-2 opacity-0 pointer-events-none transition-all duration-300 z-0 translate-y-2">
                                    <div
                                        class="flex-1 h-full flex items-center justify-between border border-gray-300 rounded-full px-1 md:px-2">
                                        <button type="button"
                                            class="btn-min w-7 md:w-8 h-full flex items-center justify-center text-gray-800 hover:text-[#06728A] active:scale-90 transition-transform cursor-pointer">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4"></path>
                                            </svg>
                                        </button>
                                        <span
                                            class="qty-display font-bold text-gray-900 text-sm w-6 text-center cursor-text">1</span>
                                        <button type="button"
                                            class="btn-plus w-7 md:w-8 h-full flex items-center justify-center text-gray-800 hover:text-[#06728A] active:scale-90 transition-transform cursor-pointer">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4">
                                                </path>
                                            </svg>
                                        </button>
                                    </div>
                                    <button type="button"
                                        class="btn-cancel w-[42px] md:w-[46px] h-full flex-shrink-0 flex items-center justify-center border border-gray-300 rounded-full text-gray-400 hover:text-red-500 hover:border-red-500 transition-colors bg-white cursor-pointer shadow-sm">
                                        <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <div class="form-submit mt-3 hidden opacity-0 transition-opacity duration-300">
                                <input type="hidden" name="quantity" class="input-qty" value="1">
                                @auth
                                    <button type="button" onclick="addToCart(this)" data-product-id="{{ $product->id }}"
                                        class="w-full bg-[#06728A] text-white hover:bg-[#055c70] rounded-full py-2 md:py-2.5 text-xs md:text-sm font-bold flex items-center justify-center gap-1.5 md:gap-2 shadow-sm active:scale-95 transition-all cursor-pointer">
                                        <svg class="w-3.5 h-3.5 md:w-4 md:h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                                            </path>
                                        </svg>
                                        Tambah
                                    </button>
                                @else
                                    <a href="{{ route('login') }}"
                                        class="w-full bg-[#06728A] text-white hover:bg-[#055c70] rounded-full py-2 md:py-2.5 text-xs md:text-sm font-bold flex items-center justify-center gap-1.5 md:gap-2 shadow-sm active:scale-95 transition-all cursor-pointer">
                                        <svg class="w-3.5 h-3.5 md:w-4 md:h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                                            </path>
                                        </svg>
                                        Login
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8 flex justify-center w-full overflow-x-auto pb-4 scrollbar-hide">
                <div class="min-w-max">
                    {{ $products->links() }}
                </div>
            </div>
        @endif

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
        document.addEventListener('DOMContentLoaded', function() {

            function closeCard(card) {
                const btnTrigger = card.querySelector('.btn-trigger');
                const qtyControls = card.querySelector('.qty-controls');
                const formSubmit = card.querySelector('.form-submit');
                const qtyDisplay = card.querySelector('.qty-display');
                const inputQty = card.querySelector('.input-qty');

                if (qtyDisplay) qtyDisplay.textContent = '1';
                if (inputQty) inputQty.value = '1';

                qtyControls.classList.remove('opacity-100', 'z-20');
                qtyControls.classList.add('opacity-0', 'pointer-events-none', 'translate-y-2');
                formSubmit.classList.add('opacity-0');
                setTimeout(() => {
                    if (!card.classList.contains('is-open')) {
                        formSubmit.classList.add('hidden');
                    }
                }, 300);

                btnTrigger.classList.remove('opacity-0', 'pointer-events-none', '-translate-y-2');
                card.classList.remove('is-open');
            }

            document.body.addEventListener('click', function(e) {
                // Buka Accordion
                if (e.target.closest('.btn-trigger')) {
                    const btnTrigger = e.target.closest('.btn-trigger');
                    const productCard = btnTrigger.closest('.product-card');

                    document.querySelectorAll('.product-card.is-open').forEach(card => {
                        if (card !== productCard) closeCard(card);
                    });

                    productCard.classList.add('is-open');

                    const qtyControls = productCard.querySelector('.qty-controls');
                    const formSubmit = productCard.querySelector('.form-submit');

                    btnTrigger.classList.add('opacity-0', 'pointer-events-none', '-translate-y-2');
                    qtyControls.classList.remove('opacity-0', 'pointer-events-none', 'translate-y-2');
                    qtyControls.classList.add('opacity-100', 'z-20');
                    formSubmit.classList.remove('hidden');
                    setTimeout(() => formSubmit.classList.remove('opacity-0'), 50);
                }

                // Tutup (Cancel)
                if (e.target.closest('.btn-cancel')) {
                    const productCard = e.target.closest('.btn-cancel').closest('.product-card');
                    closeCard(productCard);
                }

                // Kurangi Qty
                if (e.target.closest('.btn-min')) {
                    const productCard = e.target.closest('.btn-min').closest('.product-card');
                    const qtyDisplay = productCard.querySelector('.qty-display');
                    const inputQty = productCard.querySelector('.input-qty');
                    let qty = parseInt(inputQty.value);
                    if (qty > 1) {
                        qty--;
                        qtyDisplay.textContent = qty;
                        inputQty.value = qty;
                    }
                }

                // Tambah Qty
                if (e.target.closest('.btn-plus')) {
                    const productCard = e.target.closest('.btn-plus').closest('.product-card');
                    const qtyDisplay = productCard.querySelector('.qty-display');
                    const inputQty = productCard.querySelector('.input-qty');
                    let qty = parseInt(inputQty.value);
                    if (qty < 99) {
                        qty++;
                        qtyDisplay.textContent = qty;
                        inputQty.value = qty;
                    }
                }
            });
        });

        // Eksekusi AJAX ke Keranjang
        function addToCart(button) {
            const productId = button.getAttribute('data-product-id');
            const container = button.closest('.product-card');
            const quantityInput = container.querySelector('.input-qty');
            const quantity = quantityInput ? parseInt(quantityInput.value) : 1;

            const originalText = button.innerHTML;
            button.disabled = true;
            button.classList.add('opacity-75', 'cursor-not-allowed');
            button.innerHTML =
                `<svg class="animate-spin h-4 w-4 md:h-5 md:w-5 text-white" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memproses...`;

            fetch('{{ route('cart.add') }}', {
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
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        showToast(data.message);
                        const btnCancel = container.querySelector('.btn-cancel');
                        if (btnCancel) btnCancel.click(); // Tutup form setelah berhasil
                    } else {
                        alert('Gagal menambahkan produk.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Jaringan terputus. Silakan coba lagi.');
                })
                .finally(() => {
                    button.disabled = false;
                    button.classList.remove('opacity-75', 'cursor-not-allowed');
                    button.innerHTML = originalText;
                });
        }

        // Tampilkan Toast
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
