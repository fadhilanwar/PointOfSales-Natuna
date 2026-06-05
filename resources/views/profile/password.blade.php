@extends('layouts.app')

@section('content')
    <div class="px-4 py-6 md:py-10 max-w-xl mx-auto min-h-screen relative pb-24 md:pb-10">

        <div class="flex items-center gap-3 mb-8">
            <a href="{{ route('profile.index') }}"
                class="p-2 -ml-2 text-gray-500 hover:text-[#06728A] hover:bg-gray-100 rounded-full transition-all duration-300 cursor-pointer"
                title="Kembali ke Profil">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <h1 class="text-xl md:text-2xl font-bold text-[#06728A] tracking-tight cursor-default">
                Ubah Password
            </h1>
        </div>

        <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-gray-100">

            <div class="bg-[#F0F8FA] border border-[#CBE4ED] rounded-xl p-4 mb-6 flex items-start gap-3">
                <div class="w-6 h-6 rounded-full bg-[#06728A] text-white flex items-center justify-center shrink-0 mt-0.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                        </path>
                    </svg>
                </div>
                <p class="text-xs text-[#06728A] font-medium leading-relaxed cursor-default">
                    Gunakan kombinasi huruf, angka, dan simbol untuk membuat password yang kuat demi keamanan akun tokomu.
                </p>
            </div>

            <form action="{{ route('profile.password.update') }}" method="POST" class="space-y-6">
                @csrf
                @method('PATCH')

                <div>
                    <label for="current_password"
                        class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 cursor-pointer">
                        Password Saat Ini <span class="text-red-500">*</span>
                    </label>
                    <input type="password" name="current_password" id="current_password" required
                        class="w-full bg-gray-50 text-gray-900 text-sm font-medium rounded-xl px-4 py-3.5 border outline-none transition-all duration-300
                    @error('current_password') border-red-500 focus:ring-4 focus:ring-red-500/10 @else border-gray-200 focus:border-[#06728A] focus:bg-white focus:ring-4 focus:ring-[#06728A]/10 hover:border-gray-300 @enderror"
                        placeholder="Masukkan password Anda saat ini">
                    @error('current_password')
                        <p class="text-red-500 text-xs font-semibold mt-1.5 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="h-px w-full bg-gray-100 my-4"></div>

                <div>
                    <label for="password"
                        class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 cursor-pointer">
                        Password Baru <span class="text-red-500">*</span>
                    </label>
                    <input type="password" name="password" id="password" required
                        class="w-full bg-gray-50 text-gray-900 text-sm font-medium rounded-xl px-4 py-3.5 border outline-none transition-all duration-300
                    @error('password') border-red-500 focus:ring-4 focus:ring-red-500/10 @else border-gray-200 focus:border-[#06728A] focus:bg-white focus:ring-4 focus:ring-[#06728A]/10 hover:border-gray-300 @enderror"
                        placeholder="Minimal 8 karakter">
                    @error('password')
                        <p class="text-red-500 text-xs font-semibold mt-1.5 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation"
                        class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 cursor-pointer">
                        Konfirmasi Password Baru <span class="text-red-500">*</span>
                    </label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                        class="w-full bg-gray-50 text-gray-900 text-sm font-medium rounded-xl px-4 py-3.5 border outline-none transition-all duration-300
                    @error('password_confirmation') border-red-500 focus:ring-4 focus:ring-red-500/10 @else border-gray-200 focus:border-[#06728A] focus:bg-white focus:ring-4 focus:ring-[#06728A]/10 hover:border-gray-300 @enderror"
                        placeholder="Ulangi password baru Anda">
                    @error('password_confirmation')
                        <p class="text-red-500 text-xs font-semibold mt-1.5 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="pt-4">
                    <button type="submit"
                        class="w-full bg-gradient-to-r from-[#06728A] to-[#0891b2] hover:opacity-90 text-white rounded-xl py-4 text-sm font-bold tracking-wide flex items-center justify-center gap-2 transition-all shadow-md shadow-[#06728A]/20 active:scale-[0.98] cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Simpan Password Baru
                    </button>
                </div>

            </form>
        </div>
    </div>
@endsection
