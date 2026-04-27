@extends('layouts.app')

@section('content')
    <div class="px-4 py-6 md:py-8 md:px-0">

        <div class="flex justify-between items-center mb-6 md:hidden">
            <h1
                class="text-xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-[#06728A] to-[#0891b2] tracking-tight">
                Natuna Grosir</h1>
            <button
                class="p-2 text-gray-400 hover:text-[#06728A] hover:bg-[#F0F8FA] rounded-full transition-all duration-300 ease-in-out cursor-pointer relative">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                    </path>
                </svg>
                <span class="absolute top-1.5 right-1.5 h-2 w-2 rounded-full bg-red-500 border-2 border-white"></span>
            </button>
        </div>

        <div class="relative mb-6 md:hidden group cursor-pointer">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400 group-hover:text-[#06728A] transition-colors duration-300 ease-in-out"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <input type="text"
                class="w-full bg-white hover:bg-gray-50 border border-gray-100 focus:border-[#06728A] rounded-full py-3.5 pl-12 pr-4 text-sm font-medium focus:outline-none focus:ring-4 focus:ring-[#06728A]/10 shadow-sm transition-all duration-300 ease-in-out cursor-pointer"
                placeholder="Cari produk grosir...">
        </div>

        <div
            class="bg-gradient-to-br from-[#06728A] to-[#055c70] rounded-3xl p-7 md:p-10 mb-8 text-white shadow-lg shadow-[#06728A]/20 relative overflow-hidden group cursor-pointer hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300 ease-in-out">
            <div
                class="absolute -right-20 -top-20 w-64 h-64 bg-white/10 rounded-full blur-3xl group-hover:bg-white/20 transition-all duration-500">
            </div>
            <div class="absolute right-10 bottom-10 w-32 h-32 bg-[#A4D2E1]/20 rounded-full blur-2xl"></div>

            <div class="relative z-10">
                <span
                    class="inline-block bg-white/20 backdrop-blur-sm text-white border border-white/30 text-xs font-bold tracking-wide px-4 py-1.5 rounded-full mb-5 shadow-sm">
                    Kualitas Terjamin
                </span>
                <h2 class="text-3xl md:text-4xl font-bold mb-3 leading-tight tracking-tight text-white drop-shadow-sm">
                    Solusi Kebutuhan<br>Sembako
                </h2>
                <p class="text-sm md:text-base font-medium text-[#CBE4ED] w-full md:w-2/3 leading-relaxed">
                    Belanja lebih tenang dengan kepastian stok dan kualitas barang yang selalu terjaga.
                </p>
            </div>
        </div>

        @if ($activeOrder ?? true)
            <div
                class="bg-[#CBE4ED] rounded-2xl p-5 mb-8 flex items-center justify-between shadow-sm hover:shadow-md hover:border-[#A4D2E1] border border-transparent transition-all duration-300 ease-in-out cursor-pointer group">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="relative flex h-2.5 w-2.5">
                            <span
                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#0891b2] opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-[#06728A]"></span>
                        </span>
                        <h3 class="text-sm font-bold text-gray-800 tracking-wide">
                            {{ $activeOrder->invoice_number ?? 'TRX-1029' }}</h3>
                    </div>
                    <p class="text-base font-semibold text-gray-800 mt-1">Sedang Dikirim</p>
                    <p class="text-xs font-medium text-gray-600 mt-1 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Kurir: {{ $activeOrder->courier->name ?? 'Budi Setiawan' }}
                    </p>
                </div>
                <div
                    class="bg-[#A4D2E1] p-3.5 rounded-2xl group-hover:bg-white/50 group-hover:scale-105 transition-all duration-300 ease-in-out">
                    <svg class="w-7 h-7 text-[#06728A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                    </svg>
                </div>
            </div>
        @endif

        <div class="mb-8">
            <h3 class="text-lg font-bold text-gray-800 mb-4 tracking-tight">Kategori Populer</h3>
            <div class="flex space-x-3 overflow-x-auto pb-2 -mx-4 px-4 md:mx-0 md:px-0 scrollbar-hide">
                <button
                    class="whitespace-nowrap px-6 py-2.5 rounded-full bg-[#06728A] text-white font-semibold text-sm shadow-md shadow-[#06728A]/20 hover:bg-[#055c70] hover:-translate-y-0.5 transition-all duration-300 ease-in-out cursor-pointer border border-transparent">Semua
                    Kategori</button>
                <button
                    class="whitespace-nowrap px-6 py-2.5 rounded-full bg-white text-gray-600 font-semibold text-sm shadow-sm hover:shadow-md hover:text-[#06728A] hover:-translate-y-0.5 border border-gray-100 transition-all duration-300 ease-in-out cursor-pointer">Sembako</button>
                <button
                    class="whitespace-nowrap px-6 py-2.5 rounded-full bg-white text-gray-600 font-semibold text-sm shadow-sm hover:shadow-md hover:text-[#06728A] hover:-translate-y-0.5 border border-gray-100 transition-all duration-300 ease-in-out cursor-pointer">Minuman</button>
                <button
                    class="whitespace-nowrap px-6 py-2.5 rounded-full bg-white text-gray-600 font-semibold text-sm shadow-sm hover:shadow-md hover:text-[#06728A] hover:-translate-y-0.5 border border-gray-100 transition-all duration-300 ease-in-out cursor-pointer">Bumbu</button>
            </div>
        </div>

        <div class="flex justify-between items-end mb-5">
            <h3 class="text-xl font-bold text-gray-800 tracking-tight">Katalog Grosir</h3>
            <a href="#"
                class="text-sm font-bold text-[#06728A] flex items-center hover:text-[#055c70] group cursor-pointer transition-colors duration-300 ease-in-out">
                Lihat Semua
                <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform duration-300 ease-in-out"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6 mb-8">
            @forelse($products ?? [1,2,3,4] as $product)
                <div
                    class="bg-white rounded-2xl shadow-sm border border-gray-100/50 overflow-hidden flex flex-col hover:shadow-xl hover:shadow-[#06728A]/5 hover:-translate-y-1.5 hover:border-[#CBE4ED] transition-all duration-300 ease-in-out cursor-pointer group">

                    <div class="aspect-w-1 aspect-h-1 w-full bg-gray-50 relative overflow-hidden">
                        <img src="https://loremflickr.com/400/300/grocery?random={{ $loop->iteration }}"
                            alt="Produk"
                            class="w-full h-44 object-cover object-center group-hover:scale-105 transition-transform duration-500 ease-in-out">
                    </div>

                    <div class="p-4 md:p-5 flex flex-col flex-grow">
                        <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider mb-1">Sembako</p>

                        <h4
                            class="text-sm font-semibold text-gray-800 line-clamp-2 leading-snug group-hover:text-[#06728A] transition-colors duration-300">
                            {{ $product->name ?? 'Beras Premium Pandan Wangi 5kg' }}</h4>

                        <div class="mt-3 mb-4 flex items-baseline gap-1">
                            <span class="text-lg md:text-xl font-bold text-gray-900 tracking-tight">Rp
                                {{ number_format($product->base_price ?? 115000, 0, ',', '.') }}</span>
                            <span class="text-xs font-semibold text-gray-500">/ dus</span>
                        </div>

                        <div class="mt-auto pt-2">
                            <button
                                class="w-full bg-white border-2 border-[#06728A] text-[#06728A] hover:bg-[#06728A] hover:text-white rounded-xl py-2.5 text-sm font-bold flex items-center justify-center gap-2 hover:shadow-md hover:shadow-[#06728A]/20 active:scale-95 transition-all duration-300 ease-in-out cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                                    </path>
                                </svg>
                                Keranjang
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div
                    class="col-span-2 lg:col-span-4 text-center py-12 bg-white rounded-2xl border border-gray-100 border-dashed">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                        </path>
                    </svg>
                    <p class="text-sm font-medium text-gray-500">Katalog sedang disiapkan.</p>
                </div>
            @endforelse
        </div>

    </div>
@endsection
