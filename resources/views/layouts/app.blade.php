<!DOCTYPE html>
<html lang="id" class="antialiased">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Natuna Grosir</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#06728A">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
</head>

<body
    class="bg-gray-50/50 text-gray-800 {{ request()->routeIs(['checkout.index', 'checkout.payment']) ? 'pb-0 md:pb-0' : 'pb-20 md:pb-0' }} font-['Plus_Jakarta_Sans'] selection:bg-[#06728A] selection:text-white">

    @php
        $cartItemCount = 0;
        if (auth()->check()) {
            // Mengambil total kuantitas produk di dalam keranjang user saat ini
            $cart = \App\Models\Cart::where('user_id', auth()->id())->first();
            $cartItemCount = $cart ? $cart->items()->sum('quantity') : 0;
        }
    @endphp

    @if (!request()->routeIs(['checkout.index', 'checkout.payment']))
        <header
            class="hidden md:block bg-white/80 backdrop-blur-md shadow-sm sticky top-0 z-50 border-b border-gray-100 transition-all duration-300 ease-in-out">
            <div class="max-w-7xl mx-auto px-6 lg:px-8 py-4 flex justify-between items-center gap-6">

                <a href="{{ route('home') }}"
                    class="text-2xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-[#06728A] to-[#0891b2] tracking-tight cursor-pointer hover:opacity-80 transition-opacity duration-300 ease-in-out shrink-0">
                    Natuna Grosir
                </a>

                <div class="flex-1 max-w-xl relative group cursor-pointer ml-4">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400 group-hover:text-[#06728A] transition-colors duration-300 ease-in-out"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text"
                        class="w-full bg-gray-100 hover:bg-gray-200/50 focus:bg-white border border-transparent focus:border-[#06728A] rounded-full py-2.5 pl-12 pr-4 text-sm font-medium focus:outline-none focus:ring-4 focus:ring-[#06728A]/10 shadow-sm hover:shadow transition-all duration-300 ease-in-out cursor-text"
                        placeholder="Cari beras, minyak, tepung...">
                </div>

                <div class="flex items-center gap-2 text-gray-600 shrink-0">
                    @auth
                        <a href="{{ route('orders.index') }}"
                            class="px-3 py-2 rounded-xl hover:bg-[#F0F8FA] hover:text-[#06728A] font-medium transition-all duration-300 ease-in-out cursor-pointer flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                </path>
                            </svg>
                            Pesanan
                        </a>

                        <a href="{{ route('cart.index') }}"
                            class="px-3 py-2 rounded-xl hover:bg-[#F0F8FA] hover:text-[#06728A] font-medium transition-all duration-300 ease-in-out cursor-pointer flex items-center gap-2 relative">
                            <div class="relative">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                                    </path>
                                </svg>
                                @if ($cartItemCount > 0)
                                    <span
                                        class="absolute -top-1.5 -right-2 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-[9px] font-bold text-white border-2 border-white shadow-sm transform scale-110">
                                        {{ $cartItemCount > 99 ? '99+' : $cartItemCount }}
                                    </span>
                                @endif
                            </div>
                            Keranjang
                        </a>

                        <div class="w-px h-6 bg-gray-200 mx-2"></div>

                        <a href="{{ url('/profile') }}"
                            class="flex items-center justify-center w-10 h-10 rounded-full border-2 border-transparent hover:border-[#06728A] transition-all duration-300 cursor-pointer overflow-hidden p-0.5 bg-gray-50 ml-1"
                            title="Profil & Pengaturan">
                            @if (auth()->user()->profile_photo_path)
                                <img src="{{ asset('storage/' . auth()->user()->profile_photo_path) }}"
                                    alt="Profile" class="w-full h-full rounded-full object-cover shadow-sm">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->shop_name ?? auth()->user()->username) }}&color=06728A&background=CBE4ED&bold=true"
                                    alt="Profile" class="w-full h-full rounded-full object-cover shadow-sm">
                            @endif
                        </a>
                        <form action="{{ route('logout') }}" method="POST" class="m-0 p-0">
                            @csrf
                            <button type="submit"
                                class="p-2 rounded-lg text-gray-400 hover:text-red-500 hover:bg-red-50 font-medium transition-all duration-300 ease-in-out cursor-pointer"
                                title="Keluar">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                    </path>
                                </svg>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}"
                            class="px-6 py-2.5 rounded-xl bg-[#06728A] text-white hover:bg-[#055c70] font-bold transition-all duration-300 ease-in-out flex items-center gap-2 shadow-md shadow-[#06728A]/20 active:scale-95 cursor-pointer">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1">
                                </path>
                            </svg>
                            Masuk
                        </a>
                    @endauth
                </div>
            </div>
        </header>
    @endif

    <main class="max-w-7xl mx-auto md:px-6 lg:px-8 selection:bg-[#06728A] selection:text-white relative">
        @yield('content')
    </main>

    @if (!request()->routeIs(['checkout.index', 'checkout.payment']))
        <nav
            class="md:hidden fixed bottom-0 left-0 w-full bg-white/90 backdrop-blur-md border-t border-gray-100 z-50 flex justify-around items-center py-2 px-2 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]">
            <a href="{{ route('home') }}"
                class="flex flex-col items-center p-2 {{ request()->routeIs('home') ? 'text-[#06728A]' : 'text-gray-400 hover:text-[#06728A]' }} cursor-pointer transition-all duration-300 ease-in-out hover:-translate-y-1">
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                    </path>
                </svg>
                <span class="text-[10px] font-bold tracking-wide">BERANDA</span>
            </a>

            <a href="{{ auth()->check() ? route('cart.index') : route('login') }}"
                class="flex flex-col items-center p-2 {{ request()->routeIs('cart.index') ? 'text-[#06728A]' : 'text-gray-400 hover:text-[#06728A]' }} cursor-pointer transition-all duration-300 ease-in-out hover:-translate-y-1 relative">
                <div class="relative mb-1">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                    @if (auth()->check() && $cartItemCount > 0)
                        <span
                            class="absolute -top-1 -right-1.5 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-[8px] font-bold text-white border border-white">
                            {{ $cartItemCount > 99 ? '99+' : $cartItemCount }}
                        </span>
                    @endif
                </div>
                <span class="text-[10px] font-medium tracking-wide">KERANJANG</span>
            </a>

            <a href="{{ auth()->check() ? route('orders.index') : route('login') }}"
                class="flex flex-col items-center p-2 {{ request()->routeIs(['orders.index', 'orders.show']) ? 'text-[#06728A]' : 'text-gray-400 hover:text-[#06728A]' }} cursor-pointer transition-all duration-300 ease-in-out hover:-translate-y-1">
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                    </path>
                </svg>
                <span class="text-[10px] font-medium tracking-wide">PESANAN</span>
            </a>


            @auth
                <a href="{{ url('/profile') }}"
                    class="flex flex-col items-center p-2 text-gray-400 hover:text-[#06728A] cursor-pointer transition-all duration-300 ease-in-out hover:-translate-y-1">
                    <div
                        class="w-6 h-6 rounded-full overflow-hidden border-2 border-transparent hover:border-[#06728A] mb-1">
                        @if (auth()->user()->profile_photo_path)
                            <img src="{{ asset('storage/' . auth()->user()->profile_photo_path) }}"
                                class="w-full h-full object-cover" alt="Profile">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->shop_name ?? auth()->user()->username) }}&color=06728A&background=CBE4ED&bold=true"
                                class="w-full h-full object-cover" alt="Profile">
                        @endif
                    </div>
                    <span class="text-[10px] font-medium tracking-wide">PROFIL</span>
                </a>
            @else
                <a href="{{ route('login') }}"
                    class="flex flex-col items-center p-2 text-gray-400 hover:text-[#06728A] cursor-pointer transition-all duration-300 ease-in-out hover:-translate-y-1">
                    <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1">
                        </path>
                    </svg>
                    <span class="text-[10px] font-medium tracking-wide">LOGIN</span>
                </a>
            @endauth
        </nav>
    @endif
</body>

</html>
