@extends('layouts.app')

@section('content')
    <form action="{{ route('checkout.index') }}" method="GET" id="cart-form">
        <div class="px-4 py-6 md:py-8 md:px-0 max-w-3xl mx-auto relative min-h-screen flex flex-col" id="cart-container">

            <div class="flex items-center justify-between mb-6">
                <a href="{{ route('home') }}"
                    class="p-2 -ml-2 text-gray-400 hover:text-[#06728A] transition-colors cursor-pointer rounded-full hover:bg-[#F0F8FA]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <h1 class="text-xl font-bold text-[#06728A] tracking-tight">Keranjang Belanja</h1>
                <div class="w-8"></div>
            </div>

            @if ($cart && $cart->items->count() > 0)
                <div class="mb-4 px-2 flex items-center gap-3">
                    <input type="checkbox" id="select-all"
                        class="w-5 h-5 text-[#06728A] rounded border-gray-300 focus:ring-[#06728A] cursor-pointer">
                    <label for="select-all" class="text-sm font-bold text-gray-700 cursor-pointer select-none">Pilih Semua
                        Produk</label>
                </div>

                <div class="flex-grow space-y-4 mb-32" id="cart-items-wrapper">
                    @foreach ($cart->items as $item)
                        <div class="cart-item bg-white rounded-2xl p-4 flex gap-4 shadow-sm border border-gray-50 hover:shadow-md transition-all duration-300 relative group"
                            data-id="{{ $item->id }}" data-price="{{ $item->product->base_price }}"
                            data-quantity="{{ $item->quantity }}">

                            <button type="button"
                                class="btn-remove absolute top-3 right-3 text-gray-300 hover:text-red-500 hover:bg-red-50 p-1.5 rounded-full transition-colors cursor-pointer z-10">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>

                            <div class="flex items-center justify-center pt-5">
                                <input type="checkbox" name="selected_cart_ids[]" value="{{ $item->id }}"
                                    class="item-checkbox w-5 h-5 text-[#06728A] rounded border-gray-300 focus:ring-[#06728A] cursor-pointer">
                            </div>

                            <div
                                class="w-16 h-16 md:w-20 md:h-20 bg-gray-100 rounded-xl overflow-hidden shrink-0 flex items-center justify-center cursor-pointer">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($item->product->name) }}&color=06728A&background=CBE4ED"
                                    class="w-full h-full object-cover">
                            </div>

                            <div class="flex flex-col flex-grow justify-between">
                                <div class="pr-6">
                                    <h3 class="text-sm font-semibold text-gray-800 leading-snug cursor-pointer">
                                        {{ $item->product->name }}</h3>
                                    <p class="text-[11px] text-gray-500 mt-0.5 font-medium">Rp
                                        {{ number_format($item->product->base_price, 0, ',', '.') }} / dus</p>
                                </div>

                                <div class="flex items-center justify-between mt-3">
                                    <div class="flex items-center bg-gray-50 border border-gray-100 rounded-full p-1">
                                        <button type="button"
                                            class="btn-decrease w-8 h-8 flex items-center justify-center text-gray-500 hover:text-[#06728A] hover:bg-white rounded-full transition-all shadow-sm active:scale-95 cursor-pointer disabled:opacity-50">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M20 12H4"></path>
                                            </svg>
                                        </button>
                                        <span
                                            class="item-qty w-8 text-center text-sm font-bold text-gray-800">{{ $item->quantity }}</span>
                                        <button type="button"
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
                            <span class="text-gray-500 font-medium" id="summary-count">Subtotal (0 Produk)</span>
                            <span class="font-bold text-gray-800" id="summary-subtotal">Rp 0</span>
                        </div>

                        <div class="flex justify-between items-end mb-4">
                            <span class="text-base font-bold text-gray-800">Total Dibayar</span>
                            <span class="text-xl md:text-2xl font-bold text-[#06728A] tracking-tight" id="summary-total">Rp
                                0</span>
                        </div>

                        <button type="submit" id="btn-checkout" disabled
                            class="w-full bg-[#06728A] hover:bg-[#055c70] disabled:bg-gray-300 disabled:cursor-not-allowed text-white rounded-2xl py-3.5 text-sm font-bold tracking-wide flex items-center justify-center gap-2 transition-all shadow-md active:scale-[0.98] cursor-pointer">
                            <span>LANJUT PEMBAYARAN</span>
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
                    <p class="text-gray-500 mb-8 text-center text-sm">Ayo mulai belanja kebutuhan sembako dengan harga
                        terbaik.</p>
                    <a href="{{ route('home') }}"
                        class="px-8 py-3 bg-[#06728A] text-white font-bold rounded-full shadow-md hover:bg-[#055c70] transition-colors cursor-pointer">Belanja
                        Sekarang</a>
                </div>
            @endif
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Ambil semua elemen yang dibutuhkan
            const selectAllCheckbox = document.getElementById('select-all');
            const itemCheckboxes = document.querySelectorAll('.item-checkbox');
            const btnCheckout = document.getElementById('btn-checkout');
            const summarySubtotal = document.getElementById('summary-subtotal');
            const summaryTotal = document.getElementById('summary-total');
            const summaryCount = document.getElementById('summary-count');

            // Format angka ke format Rupiah
            const formatIDR = (number) => new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(number);

            // FUNGSI UTAMA: Menghitung total harga dari item yang dicentang saja
            function calculateTotal() {
                let totalHarga = 0;
                let totalBarang = 0;

                // Loop setiap checkbox item
                itemCheckboxes.forEach(checkbox => {
                    // Jika dicentang, tambahkan harganya ke total
                    if (checkbox.checked) {
                        // Cari element kotak produk (parent) tempat checkbox ini berada
                        const cartItem = checkbox.closest('.cart-item');

                        // Ambil harga dan qty dari atribut data yang sudah kita set di HTML tadi
                        const harga = parseFloat(cartItem.getAttribute('data-price'));
                        const qty = parseInt(cartItem.getAttribute('data-quantity'));

                        totalHarga += (harga * qty);
                        totalBarang += 1;
                    }
                });

                // Update text di layar bawah (Sticky Bar)
                summarySubtotal.textContent = formatIDR(totalHarga);
                summaryTotal.textContent = formatIDR(totalHarga);
                summaryCount.textContent = `Subtotal (${totalBarang} Produk)`;

                // Jika tidak ada barang yang dicentang, disable (matikan) tombol checkout
                if (totalBarang === 0) {
                    btnCheckout.disabled = true;
                } else {
                    btnCheckout.disabled = false;
                }
            }

            // EVENT LISTENER: Saat Checkbox "Pilih Semua" di-klik
            if (selectAllCheckbox) {
                selectAllCheckbox.addEventListener('change', function() {
                    const isChecked = this.checked;

                    // Buat semua checkbox item mengikuti status "Pilih Semua"
                    itemCheckboxes.forEach(checkbox => {
                        checkbox.checked = isChecked;
                    });

                    // Hitung ulang total
                    calculateTotal();
                });
            }

            // EVENT LISTENER: Saat Checkbox Masing-Masing Item di-klik
            itemCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    // Hitung ulang total
                    calculateTotal();

                    // Logika Pintar: Jika ada 1 item yang tidak dicentang, hilangkan centang "Pilih Semua"
                    const allChecked = Array.from(itemCheckboxes).every(cb => cb.checked);
                    selectAllCheckbox.checked = allChecked;
                });
            });

            // (Opsional) Panggil kalkulasi di awal barangkali ada browser yang nge-cache checkbox centang
            calculateTotal();

            // ... (Kode Javascript lama kamu untuk tambah/kurang qty & hapus biarkan di bawah ini) ...
        });
    </script>
@endsection
