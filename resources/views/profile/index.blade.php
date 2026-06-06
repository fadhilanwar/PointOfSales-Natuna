@extends('layouts.app')

@section('content')
    <div class="px-4 py-6 md:py-10 max-w-2xl mx-auto min-h-screen relative pb-24 md:pb-10">

        <!-- Header Halaman -->
        <div class="flex items-center justify-center mb-8 relative">
            <h1 class="text-xl md:text-2xl font-bold text-[#06728A] tracking-tight cursor-default">
                Profil Saya
            </h1>
        </div>
        @if (session('success'))
            <div id="profile-alert"
                class="mb-6 bg-emerald-50 border border-emerald-200 rounded-xl p-4 flex items-start gap-3 shadow-sm transition-all duration-500">
                <div
                    class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0 mt-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm text-emerald-800 font-bold mt-0.5">{{ session('success') }}</p>
                </div>
                <button type="button" onclick="document.getElementById('profile-alert').remove()"
                    class="text-emerald-600 hover:bg-emerald-200 p-1 rounded-lg transition-colors shrink-0 cursor-pointer"
                    title="Tutup">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        @endif

        <!-- Bagian 1: Avatar & Nama Utama -->
        <div class="flex flex-col items-center justify-center mb-8">
            <div class="mb-4">
                <div
                    class="w-24 h-24 md:w-28 md:h-28 rounded-full border-4 border-white shadow-md overflow-hidden bg-[#F0F8FA] flex items-center justify-center">
                    @if ($user->profile_photo_path)
                        <img src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="Avatar User"
                            class="w-full h-full object-cover">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&color=06728A&background=CBE4ED&size=150&bold=true"
                            alt="Avatar Default" class="w-full h-full object-cover">
                    @endif
                </div>
            </div>

            <h2 class="text-2xl font-bold text-gray-900 tracking-tight text-center cursor-text">{{ $user->name }}</h2>
            <p class="text-sm font-medium text-gray-500 mt-1 cursor-text">{{ $user->shop_name ?? 'Pelanggan Reguler' }}</p>
        </div>

        <!-- Bagian 2: Informasi Detail B2B -->
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 mb-6">
            <h3 class="text-sm font-bold text-gray-900 mb-4 cursor-default">Informasi Akun & Kontak</h3>

            <div class="space-y-4">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-xl bg-gray-50 text-gray-400 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div class="pt-1">
                        <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-0.5 cursor-default">
                            Username</p>
                        <p class="text-sm font-bold text-gray-800 cursor-text">{{ $user->username }}</p>
                    </div>
                </div>

                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-xl bg-gray-50 text-gray-400 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    <div class="pt-1">
                        <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-0.5 cursor-default">Email
                            Valid</p>
                        <p class="text-sm font-bold text-gray-800 cursor-text">{{ $user->email }}</p>
                    </div>
                </div>

                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-xl bg-[#F0F8FA] text-[#06728A] flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.243-4.243a8 8 0 1111.314 0z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <div class="pt-1">
                        <p class="text-[11px] font-bold text-[#06728A] uppercase tracking-wider mb-0.5 cursor-default">
                            Alamat Pengiriman Utama</p>
                        <p class="text-sm font-medium text-gray-600 leading-relaxed cursor-text">
                            {{ $user->address ?? 'Alamat belum diatur. Silakan perbarui di menu Edit Profil.' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bagian 3: Navigasi Menu & Pengaturan -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden mb-6">

            <!-- Link Edit Profil -->
            <a href="{{ route('profile.edit') }}"
                class="flex items-center justify-between p-5 border-b border-gray-50 hover:bg-gray-50 transition-colors group cursor-pointer">
                <div class="flex items-center gap-4">
                    <div
                        class="w-10 h-10 rounded-xl bg-gray-50 text-gray-600 group-hover:bg-white group-hover:shadow-sm transition-all flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                            </path>
                        </svg>
                    </div>
                    <span class="font-bold text-gray-800 text-sm">Edit Profil</span>
                </div>
                <svg class="w-5 h-5 text-gray-400 group-hover:text-[#06728A] transition-colors" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>

            <!-- Link Ubah Password -->
            <a href="{{ route('profile.password.edit') }}"
                class="flex items-center justify-between p-5 border-b border-gray-50 hover:bg-gray-50 transition-colors group cursor-pointer">
                <div class="flex items-center gap-4">
                    <div
                        class="w-10 h-10 rounded-xl bg-gray-50 text-gray-600 group-hover:bg-white group-hover:shadow-sm transition-all flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                            </path>
                        </svg>
                    </div>
                    <span class="font-bold text-gray-800 text-sm">Ubah Password</span>
                </div>
                <svg class="w-5 h-5 text-gray-400 group-hover:text-[#06728A] transition-colors" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>

            <!-- Link Hubungi Bantuan -->
            <a href="https://wa.me/6281234567890" target="_blank"
                class="flex items-center justify-between p-5 border-b border-gray-50 hover:bg-gray-50 transition-colors group cursor-pointer">
                <div class="flex items-center gap-4">
                    <div
                        class="w-10 h-10 rounded-xl bg-gray-50 text-gray-600 group-hover:bg-white group-hover:shadow-sm transition-all flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>
                        </svg>
                    </div>
                    <span class="font-bold text-gray-800 text-sm">Pusat Bantuan (CS)</span>
                </div>
                <svg class="w-5 h-5 text-gray-400 group-hover:text-[#06728A] transition-colors" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>

            <!-- Tombol Logout Menggunakan FORM agar aman dari CSRF -->
            <form method="POST" action="{{ route('logout') }}" class="m-0 p-0 w-full block">
                @csrf
                <button type="submit"
                    class="w-full flex items-center justify-between p-5 hover:bg-red-50 transition-colors group cursor-pointer">
                    <div class="flex items-center gap-4">
                        <div
                            class="w-10 h-10 rounded-xl bg-red-50 text-red-500 group-hover:bg-white group-hover:shadow-sm transition-all flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                </path>
                            </svg>
                        </div>
                        <span class="font-bold text-red-600 text-sm">Keluar Akun</span>
                    </div>
                    <svg class="w-5 h-5 text-red-300 group-hover:text-red-600 transition-colors" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
            </form>

        </div>

    </div>
@endsection
