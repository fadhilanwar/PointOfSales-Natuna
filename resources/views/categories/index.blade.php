@extends('layouts.app')

@section('content')
    <div class="px-4 py-6 md:py-8 max-w-7xl mx-auto min-h-screen">

        <!-- Header Halaman (HCI: Navigasi & Konteks) -->
        <div class="flex items-center gap-3 mb-8 md:mb-10">
            <a href="{{ route('home') }}"
                class="p-2 -ml-2 text-gray-500 hover:text-[#06728A] hover:bg-gray-100 rounded-full transition-all duration-300 cursor-pointer"
                title="Kembali ke Beranda">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div class="flex flex-col">
                <h1 class="text-xl md:text-2xl font-bold text-gray-900 tracking-tight leading-snug">
                    Semua Kategori
                </h1>
                <p class="text-[13px] md:text-sm text-gray-500 font-medium mt-0.5">Jelajahi seluruh komoditas grosir kami
                </p>
            </div>
        </div>

        <!-- Pengecekan Data Kosong -->
        @if ($categories->isEmpty())
            <div
                class="flex flex-col items-center justify-center py-20 text-center bg-white rounded-3xl border border-dashed border-gray-200 shadow-sm">
                <div class="w-24 h-24 bg-[#F0F8FA] rounded-full flex items-center justify-center mb-5">
                    <svg class="w-12 h-12 text-[#A4D2E1]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                        </path>
                    </svg>
                </div>
                <h2 class="text-xl md:text-2xl font-bold text-gray-900 mb-2">Kategori Belum Tersedia</h2>
                <p class="text-gray-500 text-sm md:text-base max-w-sm mx-auto">Admin belum menambahkan kategori produk
                    apapun ke dalam katalog.</p>
            </div>
        @else
            <!-- Grid Kategori (HCI: 2 Kolom Mobile, 4-6 Kolom Desktop) -->
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4 md:gap-6 pb-20">
                @foreach ($categories as $category)
                    <!-- Card Kategori (Seluruh area adalah Link / Affordance yang kuat) -->
                    <a href="{{ route('categories.show', $category->slug) }}"
                        class="block bg-white rounded-2xl p-5 border border-gray-100 shadow-sm hover:shadow-lg hover:border-[#CBE4ED] hover:-translate-y-1.5 transition-all duration-300 text-center group cursor-pointer focus:outline-none focus:ring-4 focus:ring-[#06728A]/15">

                        <!-- Ikon Tipografi (Inisial Kategori) -->
                        <div
                            class="w-14 h-14 md:w-16 md:h-16 mx-auto rounded-full bg-[#F0F8FA] text-[#06728A] flex items-center justify-center text-xl md:text-2xl font-black mb-4 group-hover:scale-110 group-hover:bg-[#06728A] group-hover:text-white group-active:scale-95 transition-all duration-300 ease-out shadow-inner">
                            {{ strtoupper(substr($category->category_name, 0, 1)) }}
                        </div>

                        <!-- Informasi Teks -->
                        <h3
                            class="text-sm md:text-base font-bold text-gray-800 group-hover:text-[#06728A] transition-colors line-clamp-2 leading-tight mb-1.5">
                            {{ $category->category_name }}
                        </h3>

                        <!-- Jumlah Produk (Penting untuk Information Scent) -->
                        <p class="text-xs font-semibold text-gray-400 flex items-center justify-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            {{ $category->products_count ?? 0 }} Produk
                        </p>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
@endsection
