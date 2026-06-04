@extends('layouts.app')

@section('content')
    <div class="px-4 py-6 md:py-8 max-w-2xl mx-auto min-h-screen relative">

        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('orders.index') }}"
                class="p-2 -ml-2 text-gray-500 hover:text-[#06728A] hover:bg-gray-100 rounded-full transition-all duration-300 cursor-pointer">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <h1 class="text-xl md:text-2xl font-bold text-[#06728A] tracking-tight cursor-default">Pembayaran</h1>
        </div>

        @if (session('error') || $errors->any())
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-r-xl shadow-sm">
                <p class="font-bold text-sm">Terjadi Kesalahan:</p>
                <ul class="list-disc list-inside text-xs mt-1">
                    @if (session('error'))
                        <li>{{ session('error') }}</li>
                    @endif
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
            <div class="p-5 md:p-6 flex justify-between items-center">
                <div>
                    <p class="text-sm font-semibold text-gray-500 mb-1 cursor-default">Total Tagihan</p>
                    <p class="text-2xl md:text-3xl font-bold text-[#06728A] tracking-tight cursor-text">
                        Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                    </p>
                </div>
                <button type="button" onclick="copyToClipboard('{{ $order->grand_total }}', this)"
                    class="flex items-center gap-1.5 px-4 py-2 bg-white border-2 border-gray-100 text-gray-700 rounded-xl text-sm font-bold hover:border-[#06728A] hover:text-[#06728A] transition-all active:scale-95 shadow-sm cursor-pointer group">
                    <svg class="w-4 h-4 text-gray-400 group-hover:text-[#06728A] transition-colors" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z">
                        </path>
                    </svg>
                    <span>Salin</span>
                </button>
            </div>
            <div class="bg-orange-50/80 px-5 py-3.5 border-t border-orange-100 flex items-start gap-3">
                <svg class="w-5 h-5 text-orange-500 shrink-0 mt-0.5" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-sm text-orange-800 font-medium leading-snug cursor-text">Mohon Transfer <strong
                        class="text-orange-900">sebelum</strong> waktu yang diberikan berakhir agar pesanan tidak dibatalkan
                    otomatis.</p>
            </div>
        </div>

        @if ($banks->count() > 0)
            @php $defaultBank = $banks->first(); @endphp
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 md:p-6 mb-6 relative overflow-hidden">

                <div
                    class="absolute top-0 right-0 bg-gray-100 border-b border-l border-gray-200 text-gray-500 text-[9px] font-bold uppercase tracking-widest px-3 py-1.5 rounded-bl-xl cursor-default pointer-events-none select-none">
                    Rekening Tujuan
                </div>

                <div class="flex justify-between items-start mb-6 mt-3">
                    <div class="flex items-center gap-4">
                        <div id="active-bank-icon"
                            class="w-12 h-12 bg-blue-50 text-blue-700 rounded-xl flex items-center justify-center font-black text-xl border border-blue-100 italic tracking-tighter cursor-default transition-all select-none">
                            {{ strtoupper(substr($defaultBank->bank_name, 0, 3)) }}
                        </div>
                        <div>
                            <h3 id="active-bank-name" class="font-bold text-gray-900 text-base cursor-text transition-all">
                                Bank {{ $defaultBank->bank_name }}</h3>
                            <p class="text-xs text-gray-500 font-medium cursor-text">Transfer Manual / Virtual Account</p>
                        </div>
                    </div>

                    @if ($banks->count() > 1)
                        <button type="button" onclick="openBankModal()"
                            class="flex items-center gap-1.5 text-xs font-bold text-[#06728A] border border-[#06728A]/30 bg-white hover:bg-[#F0F8FA] px-3 py-1.5 rounded-lg transition-all active:scale-95 shadow-sm mt-1 cursor-pointer">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                            </svg>
                            Ganti Bank
                        </button>
                    @endif
                </div>

                <div class="space-y-4 bg-gray-50/50 p-4 rounded-xl border border-gray-100">
                    <div class="border-b border-gray-200 pb-4 flex justify-between items-end">
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1 cursor-default">
                                Nomor Rekening</p>
                            <p id="active-bank-acc"
                                class="text-lg font-bold text-gray-900 tracking-wider font-mono cursor-text transition-all">
                                {{ $defaultBank->account_number }}</p>
                        </div>
                        <button type="button" id="btn-copy-acc"
                            onclick="copyToClipboard('{{ $defaultBank->account_number }}', this)"
                            class="flex items-center gap-1.5 px-3 py-1.5 bg-white shadow-sm border-2 border-gray-100 text-gray-700 hover:text-[#06728A] hover:border-[#06728A] rounded-lg text-xs font-bold transition-all active:scale-95 cursor-pointer group">
                            <svg class="w-3.5 h-3.5 text-gray-400 group-hover:text-[#06728A] transition-colors"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z">
                                </path>
                            </svg>
                            <span>Salin</span>
                        </button>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1 cursor-default">Nama
                            Pemilik Rekening</p>
                        <p id="active-bank-owner" class="text-sm font-bold text-gray-900 cursor-text transition-all">
                            {{ $defaultBank->account_name }}</p>
                    </div>
                </div>
            </div>
        @else
            <div
                class="bg-white rounded-2xl shadow-sm border border-orange-200 p-5 md:p-6 mb-6 flex flex-col items-center justify-center text-center">
                <svg class="w-10 h-10 text-orange-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                    </path>
                </svg>
                <p class="text-sm font-bold text-gray-800">Informasi Rekening Belum Tersedia</p>
                <p class="text-xs text-gray-500 mt-1">Silakan hubungi admin Natuna GAS untuk informasi rekening transfer.
                </p>
            </div>
        @endif

        <form action="{{ route('checkout.payment.upload', $order->id) }}" method="POST" enctype="multipart/form-data"
            id="payment-form">
            @csrf
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 md:p-6 mb-8">
                <div class="flex justify-between items-center mb-6 border-b border-gray-100 pb-4">
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1 cursor-default">No.
                            Pesanan</p>
                        <p class="text-base font-bold text-gray-900 font-mono cursor-text">{{ $order->invoice_number }}</p>
                    </div>

                    <div
                        class="flex items-center gap-1.5 bg-amber-50 border border-amber-200 text-amber-700 px-2.5 py-1 rounded-md cursor-default select-none">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-[10px] font-bold uppercase tracking-wider">Menunggu Bayar</span>
                    </div>
                </div>

                <h3 class="font-bold text-gray-900 mb-3 flex items-center gap-2 cursor-default">
                    <svg class="w-5 h-5 text-[#06728A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                    </svg>
                    Upload Bukti Transfer
                </h3>

                <div class="relative group cursor-pointer mb-6">
                    <input type="file" name="payment_proof" id="payment_proof"
                        accept="image/jpeg,image/png,image/jpg"
                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20" required
                        onchange="previewFile(this)">

                    <div class="border-2 border-dashed border-[#A4D2E1] rounded-2xl p-6 text-center bg-[#F0F8FA]/50 group-hover:bg-[#F0F8FA] transition-colors flex flex-col items-center justify-center min-h-[180px]"
                        id="dropzone-ui">
                        <div id="dropzone-default"
                            class="flex flex-col items-center justify-center transition-all duration-300 opacity-100">
                            <div
                                class="w-14 h-14 bg-white rounded-full shadow-sm border border-gray-100 flex items-center justify-center mb-4 text-[#06728A] group-hover:scale-110 transition-transform">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <p class="text-sm font-bold text-[#06728A] mb-1">Klik untuk ambil foto atau upload file</p>
                            <p class="text-xs text-gray-500 font-medium">JPG, PNG (Maks. 5MB)</p>
                        </div>

                        <div id="dropzone-preview"
                            class="hidden flex-col items-center justify-center transition-all duration-300 w-full">
                            <div
                                class="relative max-w-[180px] rounded-xl overflow-hidden shadow-md border border-gray-200 bg-white p-1.5 mb-3">
                                <img id="image-preview" src="#" alt="Pratinjau Bukti"
                                    class="max-h-40 object-contain rounded-lg">
                            </div>
                            <p
                                class="text-sm font-bold text-emerald-600 mb-1 flex items-center gap-1.5 justify-center cursor-default">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Berkas siap diunggah!
                            </p>
                            <p id="file-name-text"
                                class="text-[11px] text-gray-500 font-medium truncate max-w-[240px] text-center cursor-text">
                            </p>
                        </div>
                    </div>
                </div>

                <button type="submit" id="btn-submit"
                    class="w-full bg-[#06728A] hover:bg-[#055c70] text-white rounded-xl py-4 text-base font-bold tracking-wide flex items-center justify-center gap-2 transition-all shadow-lg shadow-[#06728A]/20 active:scale-[0.98] cursor-pointer">
                    Kirim Konfirmasi
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                </button>
                <p class="text-center text-[10px] italic text-gray-400 mt-4 cursor-default">*Bukti transfer akan ditinjau
                    segera oleh Admin*</p>
            </div>
        </form>
    </div>

    <div id="bank-modal" class="fixed inset-0 z-[100] hidden items-end sm:items-center justify-center">
        <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity opacity-0 duration-300"
            id="bank-backdrop" onclick="closeBankModal()"></div>

        <div class="relative bg-white w-full sm:w-[400px] rounded-t-3xl sm:rounded-2xl shadow-2xl transform translate-y-full sm:translate-y-10 sm:scale-95 opacity-0 transition-all duration-300 max-h-[85vh] flex flex-col"
            id="bank-box">

            <div
                class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-white rounded-t-3xl sm:rounded-t-2xl z-10 sticky top-0">
                <h3 class="font-bold text-gray-900 text-lg cursor-default">Pilih Bank Tujuan</h3>
                <button type="button" onclick="closeBankModal()"
                    class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-full transition-colors cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <div class="p-4 overflow-y-auto custom-scrollbar flex-1">
                <div class="space-y-3">
                    @foreach ($banks as $bank)
                        <label class="relative cursor-pointer block group">
                            <input type="radio" name="select_bank" value="{{ $bank->id }}" class="peer sr-only"
                                onchange="selectBank({{ $bank->id }})" {{ $loop->first ? 'checked' : '' }}>
                            <div
                                class="flex items-center gap-4 p-4 bg-white border-2 border-gray-100 rounded-xl hover:bg-gray-50 peer-checked:border-[#06728A] peer-checked:bg-[#F0F8FA] transition-all duration-200">
                                <div
                                    class="w-10 h-10 bg-gray-100 text-gray-600 peer-checked:bg-blue-50 peer-checked:text-blue-700 rounded-lg flex items-center justify-center font-bold text-sm border border-transparent peer-checked:border-blue-100 italic transition-colors">
                                    {{ strtoupper(substr($bank->bank_name, 0, 3)) }}
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-bold text-sm text-gray-800 peer-checked:text-[#06728A]">Bank
                                        {{ $bank->bank_name }}</h4>
                                    <p class="text-[11px] text-gray-500 font-mono mt-0.5">{{ $bank->account_number }}</p>
                                </div>
                                <div
                                    class="w-5 h-5 rounded-full border-2 border-gray-300 flex items-center justify-center peer-checked:border-[#06728A]">
                                    <div
                                        class="w-2.5 h-2.5 rounded-full bg-[#06728A] scale-0 peer-checked:scale-100 transition-transform">
                                    </div>
                                </div>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div id="toast"
        class="fixed bottom-10 left-1/2 transform -translate-x-1/2 bg-gray-900 text-white px-5 py-3 rounded-full text-sm font-medium opacity-0 transition-opacity duration-300 pointer-events-none z-50 shadow-xl flex items-center gap-2">
        <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>
        Teks berhasil disalin!
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>

    <script>
        const banksData = @json($banks);
        const bankModal = document.getElementById('bank-modal');
        const bankBackdrop = document.getElementById('bank-backdrop');
        const bankBox = document.getElementById('bank-box');

        function openBankModal() {
            bankModal.classList.remove('hidden');
            bankModal.classList.add('flex');
            setTimeout(() => {
                bankBackdrop.classList.remove('opacity-0');
                bankBackdrop.classList.add('opacity-100');
                bankBox.classList.remove('opacity-0', 'translate-y-full', 'sm:translate-y-10', 'sm:scale-95');
                bankBox.classList.add('opacity-100', 'translate-y-0', 'sm:scale-100');
            }, 10);
        }

        function closeBankModal() {
            bankBackdrop.classList.remove('opacity-100');
            bankBackdrop.classList.add('opacity-0');
            bankBox.classList.remove('opacity-100', 'translate-y-0', 'sm:scale-100');
            bankBox.classList.add('opacity-0', 'translate-y-full', 'sm:translate-y-10', 'sm:scale-95');
            setTimeout(() => {
                bankModal.classList.add('hidden');
                bankModal.classList.remove('flex');
            }, 300);
        }

        function selectBank(bankId) {
            const selectedBank = banksData.find(b => b.id === bankId);
            if (selectedBank) {
                document.getElementById('active-bank-icon').textContent = selectedBank.bank_name.substring(0, 3)
                    .toUpperCase();
                document.getElementById('active-bank-name').textContent = 'Bank ' + selectedBank.bank_name;
                document.getElementById('active-bank-acc').textContent = selectedBank.account_number;
                document.getElementById('active-bank-owner').textContent = selectedBank.account_name;

                const btnCopy = document.getElementById('btn-copy-acc');
                btnCopy.setAttribute('onclick', `copyToClipboard('${selectedBank.account_number}', this)`);
                closeBankModal();
            }
        }

        function copyToClipboard(text, btnElement) {
            navigator.clipboard.writeText(text).then(() => {
                const originalHtml = btnElement.innerHTML;
                btnElement.innerHTML =
                    `<svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> <span>Disalin</span>`;
                btnElement.classList.replace('text-gray-700', 'text-emerald-700');
                btnElement.classList.replace('border-gray-100', 'border-emerald-200');
                btnElement.classList.replace('hover:border-[#06728A]', 'hover:border-emerald-200');

                const toast = document.getElementById('toast');
                toast.classList.remove('opacity-0');
                toast.classList.add('opacity-100');

                setTimeout(() => {
                    toast.classList.remove('opacity-100');
                    toast.classList.add('opacity-0');
                    btnElement.innerHTML = originalHtml;
                    btnElement.classList.replace('text-emerald-700', 'text-gray-700');
                    btnElement.classList.replace('border-emerald-200', 'border-gray-100');
                    btnElement.classList.replace('hover:border-emerald-200', 'hover:border-[#06728A]');
                }, 2000);
            }).catch(err => {
                console.error('Gagal menyalin: ', err);
                alert("Gagal menyalin teks.");
            });
        }

        function previewFile(input) {
            const defaultUi = document.getElementById('dropzone-default');
            const previewUi = document.getElementById('dropzone-preview');
            const previewImage = document.getElementById('image-preview');
            const fileNameText = document.getElementById('file-name-text');

            if (input.files && input.files[0]) {
                const file = input.files[0];
                fileNameText.textContent = file.name;
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.setAttribute('src', e.target.result);
                }
                reader.readAsDataURL(file);
                defaultUi.classList.add('hidden');
                previewUi.classList.remove('hidden');
                previewUi.classList.add('flex');
            } else {
                previewImage.setAttribute('src', '#');
                fileNameText.textContent = '';
                previewUi.classList.remove('flex');
                previewUi.classList.add('hidden');
                defaultUi.classList.remove('hidden');
            }
        }

        document.getElementById('payment-form').addEventListener('submit', function() {
            const btn = document.getElementById('btn-submit');
            btn.disabled = true;
            btn.classList.add('opacity-75', 'cursor-wait');
            btn.innerHTML =
                `<svg class="animate-spin h-5 w-5 text-white inline" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Mengunggah...`;
        });
    </script>
@endsection
