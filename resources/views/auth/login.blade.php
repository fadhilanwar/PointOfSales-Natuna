<!DOCTYPE html>
<html lang="id" class="antialiased">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Natuna Grosir - Login</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">
</head>

<body class="bg-[#F4F7F9] min-h-screen flex items-center justify-center p-4 font-['Plus_Jakarta_Sans'] text-gray-800">

    <!-- Login Container -->
    <div
        class="w-full max-w-md bg-white rounded-4xl shadow-xl shadow-[#06728A]/5 p-8 md:p-10 transition-all duration-300">

        <!-- Logo & Header -->
        <div class="flex flex-col items-center justify-center mb-8">
            <div class="flex items-center gap-2 mb-4">
                <svg class="w-8 h-8 text-[#06728A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                    </path>
                </svg>
                <h1 class="text-2xl font-bold text-[#06728A] tracking-tight">Natuna Grosir</h1>
            </div>
            <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">Welcome back</h2>
            <p class="text-sm text-gray-500 font-medium text-center">Silakan masuk ke akun Anda</p>
        </div>

        <!-- Form Login -->
        <form action="{{ route('login.process') }}" method="POST" class="space-y-5">
            @csrf

            <!-- Global Error Message -->
            @error('login_id')
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-r-lg text-sm font-medium animate-pulse"
                    role="alert">
                    {{ $message }}
                </div>
            @enderror

            <!-- Input Group: Email / Username -->
            <div class="space-y-1.5">
                <label for="login_id" class="block text-sm font-semibold text-gray-700">Email atau Username</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400 group-focus-within:text-[#06728A] transition-colors duration-300"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    <input type="text" name="login_id" id="login_id" value="{{ old('login_id') }}" required
                        placeholder="Contoh: toko_makmur atau email@domain.com"
                        class="w-full bg-white border border-gray-200 focus:border-[#06728A] focus:ring-4 focus:ring-[#06728A]/10 rounded-xl py-3 pl-10 pr-4 text-sm font-medium text-gray-800 placeholder-gray-400 outline-none transition-all duration-300">
                </div>
            </div>

            <!-- Input Group: Password -->
            <div class="space-y-1.5">
                <label for="password" class="block text-sm font-semibold text-gray-700">Password</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400 group-focus-within:text-[#06728A] transition-colors duration-300"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                            </path>
                        </svg>
                    </div>
                    <input type="password" name="password" id="password" required placeholder="••••••••"
                        class="w-full bg-white border border-gray-200 focus:border-[#06728A] focus:ring-4 focus:ring-[#06728A]/10 rounded-xl py-3 pl-10 pr-12 text-sm font-medium text-gray-800 placeholder-gray-400 outline-none transition-all duration-300 tracking-wider">

                    <!-- Toggle Password Visibility -->
                    <button type="button" onclick="togglePassword()"
                        class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-600 transition-colors focus:outline-none">
                        <svg id="eye-icon" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                            </path>
                        </svg>
                    </button>
                </div>
                @error('password')
                    <p class="text-red-500 text-xs font-medium mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Login Button -->
            <div class="pt-2">
                <button type="submit"
                    class="w-full bg-[#06728A] text-white font-bold rounded-xl py-3.5 shadow-md shadow-[#06728A]/20 hover:bg-[#055c70] hover:-translate-y-0.5 hover:shadow-lg transition-all duration-300 active:scale-[0.98]">
                    Masuk
                </button>
            </div>
        </form>

        <!-- Divider -->
        <div class="flex items-center justify-center gap-4 mt-8 mb-6">
            <div class="h-px bg-gray-200 w-full"></div>
            <span class="text-xs text-gray-400 font-semibold tracking-wide uppercase">Atau</span>
            <div class="h-px bg-gray-200 w-full"></div>
        </div>

        <!-- WhatsApp CTA -->
        <div class="text-center">
            <p class="text-sm text-gray-600 font-medium mb-3">Belum punya akun Grosir?</p>
            <!-- Tombol WA dengan warna khas WA -->
            <a href="https://wa.me/6281234567890?text=Halo%20Admin%20Natuna%2C%20saya%20ingin%20mendaftar%20akun%20grosir.%0ANama%3A%20%0ANama%20Toko%3A%20%0AAlamat%3A%20"
                target="_blank"
                class="inline-flex items-center justify-center gap-2 w-full bg-white border-2 border-[#128C7E] text-[#128C7E] font-bold rounded-xl py-3 hover:bg-[#128C7E] hover:text-white transition-all duration-300 active:scale-[0.98]">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 0 0-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z" />
                </svg>
                Chat WA Admin
            </a>
        </div>
    </div>

    <!-- Script Toggle Password -->
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                `;
            } else {
                passwordInput.type = 'password';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                `;
            }
        }
    </script>
</body>

</html>
