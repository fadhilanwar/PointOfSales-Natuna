@extends('admin.layouts.app')

@section('content')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Buku Hutang Pelanggan</h2>
            <p class="text-sm text-slate-500 mt-1">Daftar pelanggan (user) yang masih memiliki tagihan belum lunas.</p>
        </div>
    </div>

    <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
        <div class="max-w-full overflow-x-auto">
            <table class="w-full text-sm text-left text-slate-500">
                <thead class="text-xs text-slate-500 uppercase bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th scope="col" class="py-4 px-6 font-semibold">Nama / Username</th>
                        <th scope="col" class="py-4 px-6 font-semibold">Toko & Kontak</th>
                        <th scope="col" class="py-4 px-6 font-semibold text-right">Total Hutang</th>
                        <th scope="col" class="py-4 px-6 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($users as $user)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-4 px-6">
                                <p class="font-bold text-slate-800">{{ $user->name }}</p>
                                <p class="text-xs text-slate-400">{{ '@' . $user->username }} | {{ $user->email }}</p>
                            </td>
                            <td class="py-4 px-6">
                                <p class="font-medium text-slate-700">{{ $user->shop_name ?? '-' }}</p>
                                <p class="text-xs text-slate-400">{{ $user->phone_number ?? 'Belum ada no. HP' }}</p>
                            </td>

                            {{-- REVISI: KONDISI WARNA & TAMPILAN HUTANG --}}
                            <td class="py-4 px-6 text-right">
                                @if ($user->total_hutang > 0)
                                    <span class="font-bold text-red-600 text-base">
                                        Rp {{ number_format($user->total_hutang, 0, ',', '.') }}
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-700 border border-emerald-200 px-2.5 py-1 rounded-full text-xs font-bold select-none">
                                        ✓ Tidak Ada Hutang
                                    </span>
                                @endif
                            </td>

                            <td class="py-4 px-6 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.debts.show', $user->id) }}"
                                        class="p-2 text-slate-400 hover:text-[#0a7b8c] bg-slate-50 hover:bg-cyan-50 rounded-lg transition"
                                        title="Lihat Detail Transaksi">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                            </path>
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            {{-- colspan diubah jadi 4 agar pas dengan jumlah kolom --}}
                            <td colspan="4" class="py-8 text-center text-slate-500">Belum ada data pelanggan terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
