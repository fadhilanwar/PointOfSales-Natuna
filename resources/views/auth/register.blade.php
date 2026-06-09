<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Natuna GAS</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen p-4">

    <div class="bgini-white rounded-2xl shadow-sm border border-gray-100 p-8 w-full max-w-md">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-black text-[#06728A]">Natuna GAS</h1>
            <p class="text-sm text-gray-500 font-medium mt-1">Daftar akun mitra baru</p>
        </div>

        {{-- Pesan Sukses Menunggu Approval --}}
        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-100 text-emerald-600 px-4 py-3 rounded-lg text-sm mb-6 font-medium">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('register.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold text-gray-600 mb-1.5">Nama Lengkap</label>
                <input type="text" name="name" required class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 text-sm outline-none focus:border-[#06728A] focus:ring-1 focus:ring-[#06728A] transition-all">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-600 mb-1.5">Nama Toko/Warung (Opsional)</label>
                <input type="text" name="shop_name" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 text-sm outline-none focus:border-[#06728A] focus:ring-1 focus:ring-[#06728A] transition-all">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-600 mb-1.5">Nomor HP/WhatsApp</label>
                <input type="text" name="phone" required class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 text-sm outline-none focus:border-[#06728A] focus:ring-1 focus:ring-[#06728A] transition-all">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-600 mb-1.5">Email</label>
                <input type="email" name="email" required class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 text-sm outline-none focus:border-[#06728A] focus:ring-1 focus:ring-[#06728A] transition-all">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-600 mb-1.5">Password</label>
                <input id="password-input" type="password" name="password" required class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 text-sm outline-none focus:border-[#06728A] focus:ring-1 focus:ring-[#06728A] transition-all">
            </div>
            {{-- Indikator Password --}}
                <div class="mt-2 space-y-1.5">
                    <div id="req-length" class="flex items-center text-xs font-medium text-gray-400 transition-colors duration-300">
                        <svg class="w-3.5 h-3.5 mr-1.5 fill-current" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        Minimal 8 karakter
                    </div>
                    <div id="req-number" class="flex items-center text-xs font-medium text-gray-400 transition-colors duration-300">
                        <svg class="w-3.5 h-3.5 mr-1.5 fill-current" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        Terdapat minimal 1 angka
                    </div>
                    <div id="req-capital" class="flex items-center text-xs font-medium text-gray-400 transition-colors duration-300">
                        <svg class="w-3.5 h-3.5 mr-1.5 fill-current" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        Minimal 1 huruf kapital (A-Z)
                    </div>
                </div>

            <div>
                <label class="block text-xs font-bold text-gray-600 mb-1.5">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" required class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 text-sm outline-none focus:border-[#06728A] focus:ring-1 focus:ring-[#06728A] transition-all">
            </div>

            

            <button type="submit" class="w-full bg-[#06728A] hover:bg-[#055a6d] text-white font-bold py-3 rounded-lg text-sm transition-colors mt-2">
                Ajukan Pendaftaran
            </button>
        </form>

        <p class="text-center text-xs text-gray-500 font-medium mt-6">
            Sudah punya akun? <a href="{{ route('login') }}" class="text-[#06728A] hover:underline font-bold">Masuk di sini</a>
        </p>
    </div>
<script>
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('password-input');
            const reqLength = document.getElementById('req-length');
            const reqNumber = document.getElementById('req-number');
            const reqCapital = document.getElementById('req-capital'); // Deklarasi variabel kapital

            passwordInput.addEventListener('input', function(e) {
                const val = e.target.value;

                // 1. Cek panjang karakter (minimal 8)
                if (val.length >= 8) {
                    reqLength.classList.replace('text-gray-400', 'text-emerald-500');
                } else {
                    reqLength.classList.replace('text-emerald-500', 'text-gray-400');
                }

                // 2. Cek apakah ada angka (0-9)
                if (/\d/.test(val)) {
                    reqNumber.classList.replace('text-gray-400', 'text-emerald-500');
                } else {
                    reqNumber.classList.replace('text-emerald-500', 'text-gray-400');
                }

                // 3. Cek apakah ada huruf kapital (A-Z)
                if (/[A-Z]/.test(val)) {
                    reqCapital.classList.replace('text-gray-400', 'text-emerald-500');
                } else {
                    reqCapital.classList.replace('text-emerald-500', 'text-gray-400');
                }
            });
        });
    </script>
</body>
</html>
