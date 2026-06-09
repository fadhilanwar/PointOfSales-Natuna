@extends('admin.layouts.app')

@section('title', 'Pendaftaran Akun - Natuna Grosir')
@section('header_title', 'Manajemen Pendaftar')

@section('content')
    {{-- Header Section --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Pendaftaran Akun Baru</h1>
        <p class="text-sm text-gray-500 mt-1 font-medium">
            Review dan setujui mitra yang mendaftar agar bisa mengakses sistem.
        </p>
    </div>

    {{-- Flash Messages (Notifikasi Sukses/Gagal) --}}
    @if(session('success'))
        <div class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 p-4 rounded mb-6 shadow-sm">
            <p class="text-sm font-bold">{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded mb-6 shadow-sm">
            <p class="text-sm font-bold">{{ session('error') }}</p>
        </div>
    @endif

    {{-- Tabel Pendaftar --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-gray-50 flex items-center justify-between bg-gray-50/50">
            <h2 class="text-sm font-bold text-gray-900">Antrean Pendaftar ({{ $registrants->count() }})</h2>
        </div>

        @if($registrants->isEmpty())
            <div class="p-12 text-center flex flex-col items-center justify-center">
                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="text-base font-bold text-gray-800">Tidak ada antrean</h3>
                <p class="text-sm text-gray-400 font-medium mt-1">Belum ada mitra baru yang mendaftar saat ini.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-white">
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider border-b border-gray-100">Info Pendaftar</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider border-b border-gray-100">Kontak</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider border-b border-gray-100">Waktu Daftar</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider border-b border-gray-100 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-gray-50">
                        @foreach($registrants as $user)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <p class="font-bold text-gray-900">{{ $user->name }}</p>
                                    @if($user->shop_name)
                                        <p class="text-[11px] font-medium text-[#06728A] bg-[#E0F2F7] inline-block px-2 py-0.5 rounded mt-1">
                                            <i class="fas fa-store mr-1"></i> {{ $user->shop_name }}
                                        </p>
                                    @else
                                        <p class="text-[11px] font-medium text-gray-400 mt-1">Personal / Tidak ada toko</p>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-medium text-gray-700">{{ $user->email }}</p>
                                    <p class="text-xs text-gray-400 mt-0.5">{{ $user->phone ?? '-' }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-medium text-gray-700">{{ $user->created_at->format('d M Y') }}</p>
                                    <p class="text-xs text-gray-400 mt-0.5">{{ $user->created_at->format('H:i') }} WIB</p>
                                </td>
                                <td class="px-6 py-4 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-2">
                                        {{-- Tombol ACC --}}
                                        <form action="{{ route('admin.registrants.approve', $user->id) }}" method="POST" onsubmit="return confirm('Setujui pendaftar ini dan masukkan ke sistem?');">
                                            @csrf
                                            <button type="submit" class="bg-emerald-50 hover:bg-emerald-500 text-emerald-600 hover:text-white px-3 py-1.5 rounded-lg text-xs font-bold transition-colors border border-emerald-100 hover:border-emerald-500">
                                                <i class="fas fa-check mr-1"></i> Setujui
                                            </button>
                                        </form>

                                        {{-- Tombol Tolak --}}
                                        <form action="{{ route('admin.registrants.reject', $user->id) }}" method="POST" onsubmit="return confirm('Tolak dan hapus data pendaftar ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-50 hover:bg-red-500 text-red-600 hover:text-white px-3 py-1.5 rounded-lg text-xs font-bold transition-colors border border-red-100 hover:border-red-500">
                                                <i class="fas fa-times mr-1"></i> Tolak
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection