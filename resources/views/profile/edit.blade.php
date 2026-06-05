@extends('layouts.app')

@section('content')
    <div class="px-4 py-6 md:py-10 max-w-2xl mx-auto min-h-screen relative pb-24 md:pb-10">

        <div class="flex items-center gap-3 mb-8">
            <a href="{{ route('profile.index') }}"
                class="p-2 -ml-2 text-gray-500 hover:text-[#06728A] hover:bg-gray-100 rounded-full transition-all duration-300 cursor-pointer">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <h1 class="text-xl md:text-2xl font-bold text-[#06728A] tracking-tight cursor-default">Edit Profil</h1>
        </div>

        <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-gray-100">

            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PATCH')

                <div class="flex flex-col items-center justify-center pb-6 border-b border-gray-100 mb-6">
                    <div
                        class="relative w-24 h-24 mb-4 rounded-full overflow-hidden border-4 border-white shadow-md bg-[#F0F8FA]">
                        @if ($user->profile_photo_path)
                            <img id="photo-preview" src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="Preview"
                                class="w-full h-full object-cover">
                        @else
                            <img id="photo-preview"
                                src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&color=06728A&background=CBE4ED&size=150&bold=true"
                                alt="Preview Default" class="w-full h-full object-cover">
                        @endif
                    </div>

                    <label for="profile_photo" class="cursor-pointer group flex flex-col items-center">
                        <div
                            class="flex items-center gap-2 px-4 py-2 bg-[#F0F8FA] border border-[#CBE4ED] rounded-full text-[#06728A] group-hover:bg-[#E0F2F7] transition-colors active:scale-95 shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span
                                class="text-xs font-bold">{{ $user->profile_photo_path ? 'Ubah Foto Profil' : 'Tambahkan Foto Profil' }}</span>
                        </div>
                        <input type="file" name="profile_photo" id="profile_photo" class="hidden"
                            accept="image/jpeg,image/png,image/jpg" onchange="previewImage(this)">
                    </label>
                    <p class="text-[10px] font-medium text-gray-400 mt-2 cursor-default">Format JPG/PNG, Maksimal 2MB</p>

                    @error('profile_photo')
                        <div
                            class="mt-3 bg-red-50 text-red-600 px-3 py-1.5 rounded-lg text-xs font-bold flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div>
                    <label for="name"
                        class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 cursor-pointer">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                        class="w-full bg-gray-50 text-gray-900 text-sm font-medium rounded-xl px-4 py-3.5 border outline-none transition-all duration-300
                    @error('name') border-red-500 focus:ring-4 focus:ring-red-500/10 @else border-gray-200 focus:border-[#06728A] focus:bg-white focus:ring-4 focus:ring-[#06728A]/10 hover:border-gray-300 @enderror"
                        placeholder="Masukkan nama lengkap Anda">
                    @error('name')
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
                    <label for="username"
                        class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 cursor-pointer">
                        Username <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="text-gray-400 font-medium">@</span>
                        </div>
                        <input type="text" name="username" id="username" value="{{ old('username', $user->username) }}"
                            required
                            class="w-full bg-gray-50 text-gray-900 text-sm font-medium rounded-xl pl-9 pr-4 py-3.5 border outline-none transition-all duration-300
                        @error('username') border-red-500 focus:ring-4 focus:ring-red-500/10 @else border-gray-200 focus:border-[#06728A] focus:bg-white focus:ring-4 focus:ring-[#06728A]/10 hover:border-gray-300 @enderror"
                            placeholder="username_unik">
                    </div>
                    @error('username')
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
                    <label for="email"
                        class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 cursor-pointer">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                        class="w-full bg-gray-50 text-gray-900 text-sm font-medium rounded-xl px-4 py-3.5 border outline-none transition-all duration-300
                    @error('email') border-red-500 focus:ring-4 focus:ring-red-500/10 @else border-gray-200 focus:border-[#06728A] focus:bg-white focus:ring-4 focus:ring-[#06728A]/10 hover:border-gray-300 @enderror"
                        placeholder="nama@email.com">
                    @error('email')
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
                    <label for="shop_name"
                        class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 cursor-pointer">
                        Nama Toko / Instansi (Opsional)
                    </label>
                    <input type="text" name="shop_name" id="shop_name"
                        value="{{ old('shop_name', $user->shop_name) }}"
                        class="w-full bg-gray-50 text-gray-900 text-sm font-medium rounded-xl px-4 py-3.5 border outline-none transition-all duration-300
                    @error('shop_name') border-red-500 focus:ring-4 focus:ring-red-500/10 @else border-gray-200 focus:border-[#06728A] focus:bg-white focus:ring-4 focus:ring-[#06728A]/10 hover:border-gray-300 @enderror"
                        placeholder="Cth: PT Makmur Jaya / Toko Sembako Ibu">
                    @error('shop_name')
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
                    <label for="address"
                        class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 cursor-pointer">
                        Alamat Lengkap Pengiriman
                    </label>
                    <textarea name="address" id="address" rows="4"
                        class="w-full bg-gray-50 text-gray-900 text-sm font-medium rounded-xl px-4 py-3.5 border outline-none transition-all duration-300 resize-none
                    @error('address') border-red-500 focus:ring-4 focus:ring-red-500/10 @else border-gray-200 focus:border-[#06728A] focus:bg-white focus:ring-4 focus:ring-[#06728A]/10 hover:border-gray-300 @enderror"
                        placeholder="Masukkan detail jalan, RT/RW, kelurahan, kecamatan, dan kota...">{{ old('address', $user->address) }}</textarea>
                    @error('address')
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
                                d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4">
                            </path>
                        </svg>
                        Simpan Perubahan
                    </button>
                </div>

            </form>
        </div>
    </div>

    <script>
        function previewImage(input) {
            const preview = document.getElementById('photo-preview');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection
