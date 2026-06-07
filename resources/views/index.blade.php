<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Natuna Grosir - Solusi Kebutuhan Sembako</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#F0F8FA]/20 font-sans text-slate-800 antialiased overflow-x-hidden flex flex-col min-h-screen">

    <nav
        class="fixed top-0 left-0 w-full px-6 md:px-12 py-5 flex justify-between items-center z-50 bg-white/80 backdrop-blur-md border-b border-gray-100">
        <div
            class="text-2xl font-bold text-transparent bg-clip-text bg-linear-to-r from-[#06728A] to-[#0891b2] tracking-tight">
            Natuna Grosir
        </div>

        <div>
            @if (Route::has('login'))
                @auth
                    {{-- Cek jika yang login adalah admin, arahkan ke dashboard admin --}}
                    @if (auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}"
                            class="text-sm font-semibold text-[#06728A] hover:text-[#055c70] px-5 py-2 border border-[#06728A] hover:bg-[#F0F8FA] rounded-full transition-colors">
                            Dashboard Admin
                        </a>
                    @else
                        <a href="{{ route('home') }}"
                            class="text-sm font-semibold text-[#06728A] hover:text-[#055c70] px-5 py-2 border border-[#06728A] hover:bg-[#F0F8FA] rounded-full transition-colors">
                            Beranda
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}"
                        class="text-sm font-bold text-white bg-[#06728A] hover:bg-[#055c70] px-6 py-2.5 rounded-full shadow-md shadow-[#06728A]/20 transition-all active:scale-95">
                        Masuk / Login
                    </a>
                @endauth
            @endif
        </div>
    </nav>

    <div class="grow">
        <header class="relative flex flex-col items-center justify-center min-h-[85vh] px-4 pt-32 pb-12 text-center">
            <div
                class="absolute top-1/4 left-1/2 -translate-x-1/2 w-3/4 h-64 bg-[#A4D2E1] rounded-full blur-[100px] opacity-30 pointer-events-none">
            </div>

            <div class="z-10 max-w-3xl">
                <span
                    class="inline-block bg-[#CBE4ED]/60 backdrop-blur-sm text-[#06728A] border border-[#A4D2E1] text-xs font-bold tracking-wide px-4 py-1.5 rounded-full mb-5 shadow-sm">
                    Distributor Sembako Terpercaya
                </span>
                <h1 class="text-4xl md:text-6xl font-extrabold text-[#06728A] tracking-tight leading-tight mb-6">
                    Solusi Kebutuhan Sembako Anda
                </h1>
                <p class="text-lg md:text-xl text-gray-600 mb-10 leading-relaxed font-medium">
                    Belanja lebih tenang dengan kepastian stok dan kualitas barang yang selalu terjaga. Platform grosir
                    terbaik untuk suplai bisnis warung dan toko kelontong Anda.
                </p>

                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="https://wa.me/6281234567890" target="_blank"
                        class="w-full sm:w-auto px-8 py-3.5 rounded-full bg-emerald-600 text-white font-bold shadow-md hover:bg-emerald-700 hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M12.031 0C5.383 0 0 5.383 0 12.031c0 2.124.553 4.195 1.604 6.014L.25 24l6.103-1.602a11.96 11.96 0 005.678 1.433h.005c6.646 0 12.029-5.383 12.029-12.031S18.677 0 12.031 0zm0 21.823a9.968 9.968 0 01-5.086-1.385l-.364-.216-3.774.99.999-3.682-.238-.377a9.945 9.945 0 01-1.528-5.322c0-5.494 4.471-9.965 9.965-9.965 5.495 0 9.967 4.471 9.967 9.965 0 5.495-4.472 9.992-9.941 9.992h-.001zm5.467-7.466c-.3-.151-1.776-.877-2.051-.977-.275-.1-.476-.151-.676.151-.2.302-.777.977-.953 1.178-.175.201-.35.226-.651.075-.3-.151-1.267-.467-2.414-1.49-.893-.796-1.496-1.78-1.671-2.081-.175-.302-.018-.466.132-.616.134-.135.3-.352.45-.528.151-.176.201-.302.302-.503.1-.201.05-.377-.025-.528-.075-.151-.676-1.629-.926-2.232-.243-.591-.49-.51-.676-.519-.175-.01-.376-.01-.577-.01-.2 0-.526.075-.801.376-.275.302-1.052 1.03-1.052 2.511s1.077 2.914 1.227 3.115c.151.201 2.126 3.243 5.147 4.545 2.053.886 2.846.953 3.847.802 1.114-.168 3.428-1.399 3.904-2.753.476-1.354.476-2.511.326-2.753-.151-.242-.551-.389-.851-.54z" />
                        </svg>
                        Hubungi Admin
                    </a>

                    <a href="{{ route('home') }}"
                        class="w-full sm:w-auto px-8 py-3.5 rounded-full border-2 border-[#06728A] text-[#06728A] font-bold hover:bg-[#F0F8FA] hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center">
                        Masuk sebagai Tamu
                    </a>
                </div>
            </div>
        </header>

        <section class="bg-white py-16 px-6 md:px-12 border-t border-gray-50">
            <div
                class="max-w-5xl mx-auto bg-linear-to-r from-[#F0F8FA] to-[#CBE4ED]/30 rounded-3xl p-8 md:p-10 border border-[#A4D2E1]/30 shadow-sm">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                    <div>
                        <span
                            class="text-xs font-bold text-[#06728A] uppercase tracking-wider bg-white px-3 py-1 rounded-full shadow-sm border border-gray-100">Keunggulan
                            Produk</span>
                        <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mt-4 tracking-tight">Katalog Lengkap
                            dengan Standar Distribusi Terbaik</h2>
                        <p class="text-sm md:text-base text-gray-600 mt-3 leading-relaxed">
                            Kami menyediakan produk grosir pangan dengan kualitas yang diawasi ketat. Sistem inventori
                            terintegrasi menjamin barang yang Anda terima selalu dalam kondisi prima dan berumur simpan
                            panjang.
                        </p>
                        <div class="mt-6 space-y-3">
                            <div class="flex items-center gap-3 text-sm font-semibold text-gray-700">
                                <span
                                    class="shrink-0 w-5 h-5 rounded-full bg-[#06728A] text-white flex items-center justify-center text-xs">✓</span>
                                Jaminan Produk Asli & Higienis
                            </div>
                            <div class="flex items-center gap-3 text-sm font-semibold text-gray-700">
                                <span
                                    class="shrink-0 w-5 h-5 rounded-full bg-[#06728A] text-white flex items-center justify-center text-xs">✓</span>
                                Harga Transparan Langsung Distributor
                            </div>
                        </div>
                    </div>
                    <div
                        class="flex flex-col justify-center items-center md:items-end bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                        <div class="text-center md:text-right mb-6">
                            <p class="text-xs font-bold text-gray-400 uppercase">Total Produk Aktif</p>
                            <p class="text-4xl font-extrabold text-[#06728A] mt-1">400+ Pilihan</p>
                            <p class="text-xs text-gray-500 mt-1">Siap dikirim ke lokasi usaha Anda</p>
                        </div>
                        <a href="{{ route('home') }}"
                            class="w-full md:w-auto px-6 py-3 bg-[#06728A] hover:bg-[#055c70] text-white text-center font-bold text-sm rounded-full shadow-md shadow-[#06728A]/10 transition-all flex items-center justify-center gap-2 group">
                            Lihat Katalog Produk
                            <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none"
                                stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <section class="bg-white pb-16 px-6 md:px-12">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-12">
                    <h2 class="text-2xl md:text-3xl font-bold text-[#06728A] tracking-tight">Pilih Kategori Grosir</h2>
                    <p class="text-sm md:text-base text-gray-500 mt-2">Langsung eksplorasi produk yang Anda butuhkan
                        tanpa ribet</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <div
                        class="bg-[#F0F8FA] rounded-2xl p-6 border border-gray-50 flex flex-col justify-between items-start group hover:shadow-md transition duration-300">
                        <div>
                            <div class="p-3 bg-white text-[#06728A] rounded-xl font-bold inline-block mb-4 shadow-sm">🌾
                                Sembako</div>
                            <h3 class="text-lg font-bold text-gray-800 mb-2">Beras, Minyak & Gula</h3>
                            <p class="text-sm text-gray-600 mb-6">Stok komoditas utama selalu ready dengan harga
                                bersaing untuk grosir.</p>
                        </div>
                        <a href="{{ route('categories.index') }}"
                            class="text-sm font-bold text-[#06728A] group-hover:text-[#055c70] flex items-center gap-1">
                            Lihat Produk <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                </path>
                            </svg>
                        </a>
                    </div>

                    <div
                        class="bg-[#F0F8FA] rounded-2xl p-6 border border-gray-50 flex flex-col justify-between items-start group hover:shadow-md transition duration-300">
                        <div>
                            <div class="p-3 bg-white text-[#06728A] rounded-xl font-bold inline-block mb-4 shadow-sm">🥤
                                Minuman</div>
                            <h3 class="text-lg font-bold text-gray-800 mb-2">Minuman Kemasan</h3>
                            <p class="text-sm text-gray-600 mb-6">Paket kartonan air mineral, soda, dan teh untuk
                                melengkapi isi warung.</p>
                        </div>
                        <a href="{{ route('categories.index') }}"
                            class="text-sm font-bold text-[#06728A] group-hover:text-[#055c70] flex items-center gap-1">
                            Cek Katalog <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                </path>
                            </svg>
                        </a>
                    </div>

                    <div
                        class="bg-[#F0F8FA] rounded-2xl p-6 border border-gray-50 flex flex-col justify-between items-start group hover:shadow-md transition duration-300">
                        <div>
                            <div class="p-3 bg-white text-[#06728A] rounded-xl font-bold inline-block mb-4 shadow-sm">🧂
                                Bumbu</div>
                            <h3 class="text-lg font-bold text-gray-800 mb-2">Bumbu & Penyedap</h3>
                            <p class="text-sm text-gray-600 mb-6">Pilihan bumbu dapur esensial sasetan maupun kiloan
                                dengan margin keuntungan tinggi.</p>
                        </div>
                        <a href="{{ route('categories.index') }}"
                            class="text-sm font-bold text-[#06728A] group-hover:text-[#055c70] flex items-center gap-1">
                            Jelajahi <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                </path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <section class="bg-slate-50 py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto">
                <div
                    class="bg-linear-to-br from-[#06728A] to-[#055c70] rounded-3xl p-8 md:p-12 text-white shadow-xl shadow-[#06728A]/20 relative overflow-hidden group">
                    <div
                        class="absolute -right-20 -top-20 w-64 h-64 bg-white/10 rounded-full blur-3xl group-hover:bg-white/20 transition-all duration-500">
                    </div>
                    <div class="absolute right-10 bottom-10 w-32 h-32 bg-[#A4D2E1]/20 rounded-full blur-2xl"></div>

                    <div
                        class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                        <div class="max-w-xl">
                            <h2 class="text-2xl md:text-3xl font-bold mb-3 tracking-tight">Siap Penuhi Stok Warung
                                Anda?</h2>
                            <p class="text-sm md:text-base text-[#CBE4ED] leading-relaxed">
                                Nikmati kemudahan berbelanja grosir sembako dengan sistem pelacakan kurir instan dan
                                harga langsung dari distributor terpercaya.
                            </p>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto shrink-0">
                            <a href="{{ route('login') }}"
                                class="px-6 py-3 bg-white text-[#06728A] hover:bg-[#F0F8FA] rounded-full text-center text-sm font-bold shadow-md active:scale-95 transition-all">
                                Mulai Belanja
                            </a>
                            <a href="https://wa.me/6281234567890" target="_blank"
                                class="px-6 py-3 bg-white/20 hover:bg-white/30 border border-white/30 rounded-full text-center text-sm font-bold active:scale-95 transition-all">
                                Tanya Sales Admin
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <footer class="bg-[#06728A] text-white border-t border-[#055c70] mt-auto">
        <div class="max-w-6xl mx-auto px-6 md:px-12 py-10">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                <div>
                    <h3 class="text-xl font-bold tracking-tight mb-3">Natuna Grosir</h3>
                    <p class="text-sm text-[#CBE4ED] leading-relaxed">
                        Penyedia komoditas pangan dan sembako grosir skala besar dengan pengiriman cepat, aman, dan
                        bergaransi untuk wilayah operasional Anda.
                    </p>
                </div>
                <div class="md:justify-self-center">
                    <h4 class="text-sm font-bold uppercase tracking-wider text-white mb-3">Tautan Pintar</h4>
                    <ul class="space-y-2 text-sm text-[#CBE4ED]">
                        <li><a href="{{ route('home') }}" class="hover:text-white transition-colors">Katalog Toko</a>
                        </li>
                        <li><a href="{{ route('login') }}" class="hover:text-white transition-colors">Masuk Akun</a>
                        </li>
                        <li><a href="https://wa.me/6281234567890" target="_blank"
                                class="hover:text-white transition-colors">Hubungi Sales</a></li>
                    </ul>
                </div>
                <div class="md:justify-self-end text-left md:text-right">
                    <h4 class="text-sm font-bold uppercase tracking-wider text-white mb-3">Jam Operasional</h4>
                    <p class="text-sm text-[#CBE4ED]">Senin - Sabtu: 08.00 - 17.00 WIB</p>
                    <p class="text-xs text-[#A4D2E1] mt-2">Pesanan hari Minggu akan diproses pada hari kerja
                        berikutnya.</p>
                </div>
            </div>

            <div class="h-px bg-white/10 w-full mb-6"></div>

            <div class="flex flex-col sm:flex-row justify-between items-center text-xs text-[#CBE4ED] gap-2">
                <p>&copy; 2026 Natuna Grosir. Hak Cipta Dilindungi Undang-Undang.</p>
                <p class="font-medium">Sistem Grosir Sembako Modern</p>
            </div>
        </div>
    </footer>

</body>

</html>
