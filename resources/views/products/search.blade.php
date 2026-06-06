@extends('layouts.app')

@section('content')
<!-- PENTING: Tambahan pb-36 agar tombol pagination di bawah tidak tertimpa Bottom Navbar saat di-scroll -->
<div class="px-4 py-6 md:py-8 max-w-7xl mx-auto min-h-screen relative pb-36 md:pb-10">

    <!-- HEADER PENCARIAN (Information Scent - Mobile Optimized Typography) -->
    <div class="mb-6 md:mb-8">
        <div class="flex items-start md:items-center gap-2 md:gap-3 mb-1">
            <a href="{{ route('home') }}" class="p-2 -ml-2 text-gray-500 hover:text-[#06728A] hover:bg-gray-100 rounded-full transition-all duration-300 shrink-0 mt-0.5 md:mt-0 cursor-pointer" title="Kembali ke Beranda">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div class="flex flex-col">
                <h1 class="text-base md:text-2xl font-medium text-gray-700 leading-snug">
                    Hasil untuk: <span class="font-black text-gray-900 break-words">"{{ $query }}"</span>
                </h1>
                <p class="text-[13px] md:text-sm text-gray-500 font-medium mt-0.5">Ditemukan {{ $products->total() }} produk terkait</p>
            </div>
        </div>
    </div>

    <!-- LOGIKA TAMPILAN: JIKA KOSONG VS JIKA ADA HASIL -->
    @if($products->isEmpty())
        
        <!-- EMPTY STATE (HCI: Penanganan error yang ramah dengan ilustrasi jelas) -->
        <div class="flex flex-col items-center justify-center py-16 md:py-20 px-4 text-center bg-white rounded-3xl border border-dashed border-gray-200 shadow-sm mt-4">
            
            <div class="w-24 h-24 md:w-32 md:h-32 bg-[#F0F8FA] rounded-full flex items-center justify-center mb-5 md:mb-6">
                <svg class="w-12 h-12 md:w-16 md:h-16 text-[#A4D2E1]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
            </div>
            
            <h2 class="text-xl md:text-3xl font-bold text-gray-900 mb-2 tracking-tight">Oops, produk tidak ditemukan.</h2>
            <p class="text-gray-500 max-w-sm mx-auto mb-8 text-sm md:text-base leading-relaxed">
                Kata kunci <span class="font-bold text-gray-700">"{{ $query }}"</span> tidak cocok dengan produk manapun. Coba periksa ejaan atau gunakan kata umum seperti "Minyak" atau "Beras".
            </p>
            
            <a href="{{ route('home') }}" class="inline-flex items-center justify-center gap-2 px-6 md:px-8 py-3.5 bg-gradient-to-r from-[#06728A] to-[#0891b2] text-white text-sm md:text-base font-bold rounded-full shadow-md hover:shadow-lg active:scale-95 transition-all cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                Kembali ke Katalog
            </a>
        </div>

    @else
        
        <!-- GRID PRODUK (2 Kolom untuk Mobile, 3-4 untuk Desktop) -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 md:gap-6 mb-8 items-start">
            @foreach($products as $product)
                <!-- Kartu Produk: Target Area Klik (Fitts's Law) yang luas -->
                <div class="product-card bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden flex flex-col hover:shadow-lg hover:shadow-[#06728A]/5 hover:-translate-y-1 hover:border-[#CBE4ED] transition-all duration-300 group">
                    <a href="{{ route('products.show', $product) }}" class="block aspect-w-1 aspect-h-1 w-full bg-gray-50 relative overflow-hidden">
                        <img src="{{ $product->image_path ? asset('storage/' . $product->image_path) : 'https://ui-avatars.com/api/?name='.urlencode($product->name).'&color=06728A&background=CBE4ED' }}"
                            alt="{{ $product->name }}"
                            class="w-full h-36 md:h-44 object-cover object-center group-hover:scale-105 transition-transform duration-500 ease-in-out">
                    </a>

                    <div class="p-3 md:p-4 flex flex-col flex-grow">
                        <h4 class="text-[13px] md:text-sm font-bold text-gray-900 line-clamp-2 leading-snug h-[38px] md:h-[40px] group-hover:text-[#06728A] transition-colors duration-300">
                            <a href="{{ route('products.show', $product) }}" class="hover:underline outline-none">
                                {{ $product->name }}
                            </a>
                        </h4>

                        <div class="mt-2 mb-3 md:mb-4">
                            <div class="text-base md:text-xl font-bold text-[#06728A] tracking-tight">Rp {{ number_format($product->base_price, 0, ',', '.') }}</div>
                            <div class="text-[11px] md:text-[13px] font-medium text-gray-600 mt-0.5">/ {{ $product->unit ?? 'dus' }}</div>
                        </div>

                        <!-- CTA Detail Button -->
                        <div class="mt-auto">
                            <a href="{{ route('products.show', $product) }}" class="w-full flex items-center justify-center gap-1.5 bg-[#F0F8FA] text-[#06728A] border border-[#CBE4ED] active:bg-[#06728A] active:text-white md:hover:bg-[#06728A] md:hover:text-white rounded-full py-2 md:py-2.5 text-xs md:text-sm font-bold transition-colors cursor-pointer">
                                <span>Lihat Detail</span>
                                <svg class="w-3.5 h-3.5 md:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- PAGINATION (Wrapper untuk menghindari layout patah di layar super kecil) -->
        <div class="mt-8 flex justify-center w-full overflow-x-auto pb-4 scrollbar-hide">
            <div class="min-w-max">
                {{ $products->links() }}
            </div>
        </div>

    @endif

</div>
@endsection