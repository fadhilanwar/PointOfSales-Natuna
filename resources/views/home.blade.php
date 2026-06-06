@extends('layouts.app')

@section('content')
    <div class="px-4 py-6 md:py-8 md:px-0 relative">

        <div class="flex justify-between items-center mb-6 md:hidden">
            <h1
                class="text-xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-[#06728A] to-[#0891b2] tracking-tight cursor-pointer">
                Natuna Grosir
            </h1>
            @auth
                <a href="{{ url('/profile') }}"
                    class="p-1 rounded-full transition-all duration-300 ease-in-out cursor-pointer relative shadow-sm hover:scale-105 border-2 border-transparent hover:border-[#06728A]">
                    @if (auth()->user()->profile_photo_path)
                        <img src="{{ asset('storage/' . auth()->user()->profile_photo_path) }}"
                            class="w-8 h-8 rounded-full object-cover">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->shop_name ?? auth()->user()->username) }}&color=06728A&background=CBE4ED&bold=true"
                            class="w-8 h-8 rounded-full object-cover">
                    @endif
                </a>
            @else
                <a href="{{ route('login') }}"
                    class="p-2 text-gray-400 hover:text-[#06728A] hover:bg-[#F0F8FA] rounded-full transition-all duration-300 ease-in-out cursor-pointer relative">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1">
                        </path>
                    </svg>
                </a>
            @endauth
        </div>

        <!-- Search Mobile (HCI: Touch-friendly & Native Keyboard Submit) -->
        <div class="mb-6 md:hidden group">
            <form action="{{ route('products.search') }}" method="GET" class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400 group-focus-within:text-[#06728A] transition-colors duration-300 ease-in-out"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>

                <input type="search" name="q" value="{{ request('q') }}" required
                    class="w-full bg-white hover:bg-gray-50 border border-gray-100 focus:border-[#06728A] rounded-full py-3.5 pl-11 pr-12 text-sm font-medium focus:outline-none focus:ring-4 focus:ring-[#06728A]/10 shadow-sm transition-all duration-300 ease-in-out cursor-text"
                    placeholder="Cari produk...">

                <!-- Tombol Submit Mini (Visibilitas aksi yang jelas di layar kecil) -->
                <button type="submit"
                    class="absolute inset-y-1.5 right-1.5 w-10 h-10 flex items-center justify-center bg-[#06728A] text-white rounded-full active:scale-95 transition-transform shadow-sm cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
            </form>
        </div>

        <div
            class="bg-gradient-to-br from-[#06728A] to-[#055c70] rounded-3xl p-7 md:p-10 mb-8 text-white shadow-lg shadow-[#06728A]/20 relative overflow-hidden group hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300 ease-in-out cursor-default">
            <div
                class="absolute -right-20 -top-20 w-64 h-64 bg-white/10 rounded-full blur-3xl group-hover:bg-white/20 transition-all duration-500">
            </div>
            <div class="absolute right-10 bottom-10 w-32 h-32 bg-[#A4D2E1]/20 rounded-full blur-2xl"></div>

            <div class="relative z-10">
                <span
                    class="inline-block bg-white/20 backdrop-blur-sm text-white border border-white/30 text-xs font-bold tracking-wide px-4 py-1.5 rounded-full mb-5 shadow-sm">
                    Kualitas Terjamin
                </span>
                <h2
                    class="text-3xl md:text-4xl font-bold mb-3 leading-tight tracking-tight text-white drop-shadow-sm cursor-text">
                    Solusi Kebutuhan<br>Sembako
                </h2>
                <p class="text-sm md:text-base font-medium text-[#CBE4ED] w-full md:w-2/3 leading-relaxed cursor-text">
                    Belanja lebih tenang dengan kepastian stok dan kualitas barang yang selalu terjaga.
                </p>
            </div>
        </div>

        @auth
            @if ($activeOrder ?? false)
                <a href="{{ route('orders.show', $activeOrder->invoice_number) }}"
                    class="block bg-[#CBE4ED] rounded-2xl p-5 mb-8 flex items-center justify-between shadow-sm hover:shadow-md hover:border-[#A4D2E1] border border-transparent transition-all duration-300 ease-in-out cursor-pointer group">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="relative flex h-2.5 w-2.5">
                                <span
                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#0891b2] opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-[#06728A]"></span>
                            </span>
                            <h3 class="text-sm font-bold text-gray-800 tracking-wide cursor-pointer">
                                {{ $activeOrder->invoice_number }}</h3>
                        </div>
                        <p class="text-base font-semibold text-gray-800 mt-1 cursor-pointer">
                            {{ $activeOrder->status === 'shipping' ? 'Sedang Dikirim' : 'Diproses Admin' }}
                        </p>
                        <p class="text-xs font-medium text-gray-600 mt-1 flex items-center gap-1 cursor-pointer">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Kurir: {{ $activeOrder->courier->name ?? 'Internal / Belum Dialokasikan' }}
                        </p>
                    </div>
                    <div
                        class="bg-[#A4D2E1] p-3.5 rounded-2xl group-hover:bg-white/50 group-hover:scale-105 transition-all duration-300 ease-in-out cursor-pointer">
                        <svg class="w-7 h-7 text-[#06728A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                        </svg>
                    </div>
                </a>
            @endif
        @endauth

        <div class="mb-8">
            <div class="flex justify-between items-end mb-4">
                <h3 class="text-lg font-bold text-gray-800 tracking-tight">Kategori Populer</h3>
                <a href="{{ route('categories.index') }}"
                    class="text-sm font-bold text-[#06728A] flex items-center hover:text-[#055c70] group cursor-pointer transition-colors duration-300 ease-in-out">
                    Lihat Semua
                    <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform duration-300 ease-in-out cursor-pointer"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>

            <div class="flex space-x-3 overflow-x-auto pb-2 -mx-4 px-4 md:mx-0 md:px-0 scrollbar-hide">
                @foreach ($categories as $category)
                    <a href="{{ route('categories.show', $category->slug) }}"
                        class="whitespace-nowrap px-6 py-2.5 rounded-full bg-white text-gray-600 font-semibold text-sm shadow-sm hover:shadow-md hover:text-[#06728A] hover:-translate-y-0.5 border border-gray-100 transition-all duration-300 ease-in-out cursor-pointer block">
                        {{ $category->category_name }}
                    </a>
                @endforeach
            </div>
        </div>

        <div class="flex justify-between items-end mb-5">
            <h3 class="text-xl font-bold text-gray-800 tracking-tight">Katalog Grosir</h3>
            <a href="#"
                class="text-sm font-bold text-[#06728A] flex items-center hover:text-[#055c70] group cursor-pointer transition-colors duration-300 ease-in-out">
                Lihat Semua
                <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform duration-300 ease-in-out cursor-pointer"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6 mb-8 items-start">
            @forelse($products ?? [] as $product)
                <div
                    class="product-card bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden flex flex-col hover:shadow-lg hover:shadow-[#06728A]/5 hover:-translate-y-1 hover:border-[#CBE4ED] transition-all duration-300 ease-in-out group relative cursor-default">

                    <a href="{{ route('products.show', $product) }}"
                        class="block aspect-w-1 aspect-h-1 w-full bg-gray-50 relative overflow-hidden cursor-pointer">
                        <img src="{{ $product->image_path ? asset('storage/' . $product->image_path) : 'https://ui-avatars.com/api/?name=' . urlencode($product->name) . '&color=06728A&background=CBE4ED' }}"
                            alt="{{ $product->name }}"
                            class="w-full h-44 object-cover object-center group-hover:scale-105 transition-transform duration-500 ease-in-out cursor-pointer">
                    </a>

                    <div class="p-4 flex flex-col flex-grow">
                        <h4
                            class="text-sm font-bold text-gray-900 line-clamp-2 leading-snug h-[40px] group-hover:text-[#06728A] transition-colors duration-300 cursor-pointer">
                            <a href="{{ route('products.show', $product) }}" class="hover:underline outline-none">
                                {{ $product->name }}
                            </a>
                        </h4>

                        <div class="mt-2 mb-2">
                            <div class="text-xl font-bold text-[#06728A] tracking-tight cursor-text">Rp
                                {{ number_format($product->base_price, 0, ',', '.') }}</div>
                            <div class="text-[13px] font-medium text-gray-600 mt-0.5 cursor-text">/
                                {{ $product->unit ?? 'dus' }}</div>
                        </div>

                        <div class="h-px bg-gray-200 w-full mb-4 mt-1"></div>

                        <div class="mt-auto relative h-[42px] md:h-[46px]">

                            <button type="button"
                                class="btn-trigger absolute inset-0 w-full bg-[#06728A] text-white hover:bg-[#055c70] rounded-full text-sm font-bold flex items-center justify-center gap-2 shadow-sm transition-all duration-300 z-10 cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                                    </path>
                                </svg>
                                Tambah
                            </button>

                            <div
                                class="qty-controls absolute inset-0 w-full flex items-center justify-between gap-2 opacity-0 pointer-events-none transition-all duration-300 z-0 translate-y-2">
                                <div
                                    class="flex-1 h-full flex items-center justify-between border border-gray-300 rounded-full px-2">
                                    <button type="button"
                                        class="btn-min w-8 h-full flex items-center justify-center text-gray-800 hover:text-[#06728A] active:scale-90 transition-transform cursor-pointer">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4"></path>
                                        </svg>
                                    </button>
                                    <span
                                        class="qty-display font-bold text-gray-900 text-sm w-6 text-center cursor-text">1</span>
                                    <button type="button"
                                        class="btn-plus w-8 h-full flex items-center justify-center text-gray-800 hover:text-[#06728A] active:scale-90 transition-transform cursor-pointer">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4">
                                            </path>
                                        </svg>
                                    </button>
                                </div>

                                <button type="button"
                                    class="btn-cancel w-[42px] md:w-[46px] h-full flex-shrink-0 flex items-center justify-center border border-gray-300 rounded-full text-gray-400 hover:text-red-500 hover:border-red-500 transition-colors bg-white cursor-pointer shadow-sm">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                    class="w-full bg-[#06728A] text-white hover:bg-[#055c70] rounded-full py-2.5 text-sm font-bold flex items-center justify-center gap-2 shadow-sm active:scale-95 transition-all cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                                        </path>
                                    </svg>
                                    Tambah
                                </button>
                            @else
                                <a href="{{ route('login') }}"
                                    class="w-full bg-[#06728A] text-white hover:bg-[#055c70] rounded-full py-2.5 text-sm font-bold flex items-center justify-center gap-2 shadow-sm active:scale-95 transition-all cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
            @empty
                <div
                    class="col-span-2 md:col-span-3 lg:col-span-4 text-center py-12 bg-white rounded-2xl border border-gray-100 border-dashed">
                    <p class="text-sm font-medium text-gray-500">Katalog sedang disiapkan.</p>
                </div>
            @endforelse
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
        document.addEventListener('DOMContentLoaded', function() {

            // Fungsi Modular untuk Menutup Card
            function closeCard(card) {
                const btnTrigger = card.querySelector('.btn-trigger');
                const qtyControls = card.querySelector('.qty-controls');
                const formSubmit = card.querySelector('.form-submit');
                const qtyDisplay = card.querySelector('.qty-display');
                const inputQty = card.querySelector('.input-qty');

                // Reset nilai kembali ke 1
                if (qtyDisplay) qtyDisplay.textContent = '1';
                if (inputQty) inputQty.value = '1';

                // Animasi Tutup
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

                // 1. Aksi ketika tombol "Tambah" Awal diklik
                if (e.target.closest('.btn-trigger')) {
                    const btnTrigger = e.target.closest('.btn-trigger');
                    const productCard = btnTrigger.closest('.product-card');

                    // ACCORDION LOGIC: Tutup produk lain yang sedang terbuka
                    document.querySelectorAll('.product-card.is-open').forEach(card => {
                        if (card !== productCard) closeCard(card);
                    });

                    // Buka produk yang diklik
                    productCard.classList.add('is-open');

                    const qtyControls = productCard.querySelector('.qty-controls');
                    const formSubmit = productCard.querySelector('.form-submit');

                    btnTrigger.classList.add('opacity-0', 'pointer-events-none', '-translate-y-2');
                    qtyControls.classList.remove('opacity-0', 'pointer-events-none', 'translate-y-2');
                    qtyControls.classList.add('opacity-100', 'z-20');
                    formSubmit.classList.remove('hidden');
                    setTimeout(() => formSubmit.classList.remove('opacity-0'), 50);
                }

                // 2. Aksi ketika tombol "X" (Cancel) diklik
                if (e.target.closest('.btn-cancel')) {
                    const productCard = e.target.closest('.btn-cancel').closest('.product-card');
                    closeCard(productCard);
                }

                // 3. Aksi Minus
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

                // 4. Aksi Plus
                if (e.target.closest('.btn-plus')) {
                    const productCard = e.target.closest('.btn-plus').closest('.product-card');
                    const qtyDisplay = productCard.querySelector('.qty-display');
                    const inputQty = productCard.querySelector('.input-qty');
                    let qty = parseInt(inputQty.value);
                    if (qty < 99) { // Bisa diganti dengan max stock produk jika data dikirim ke frontend
                        qty++;
                        qtyDisplay.textContent = qty;
                        inputQty.value = qty;
                    }
                }
            });
        });

        // FUNGSI AJAX SUBMIT
        function addToCart(button) {
            const productId = button.getAttribute('data-product-id');
            const container = button.closest('.product-card');
            const quantityInput = container.querySelector('.input-qty');
            const quantity = quantityInput ? parseInt(quantityInput.value) : 1;

            const originalText = button.innerHTML;
            button.disabled = true;
            button.classList.add('opacity-75', 'cursor-not-allowed');
            button.innerHTML =
                `<svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memproses...`;

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
                        if (btnCancel) btnCancel.click(); // Tutup form setelah sukses
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

        // FUNGSI TOAST UI
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
