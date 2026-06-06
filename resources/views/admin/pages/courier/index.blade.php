@extends('admin.layouts.app')

@section('content')
<div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h2 class="text-2xl font-bold text-slate-800">Data Armada / Kurir</h2>
        <p class="text-sm text-slate-500 mt-1">Kelola daftar kurir dan kendaraan pengiriman Natuna Gas.</p>
    </div>
    <a href="{{ route('admin.couriers.create') }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-[#0a7b8c] py-2.5 px-5 text-sm font-semibold text-white hover:bg-[#075e6b] transition-all">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Tambah Armada
    </a>
</div>

{{-- Alert Statis --}}
@if(session('success'))
<div class="mb-4 p-4 text-sm text-emerald-800 rounded-lg bg-emerald-50 border border-emerald-200">
    {{ session('success') }}
</div>
@endif

<div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
    <div class="max-w-full overflow-x-auto">
        <table class="w-full text-sm text-left text-slate-500">
            <thead class="text-xs text-slate-500 uppercase bg-slate-50 border-b border-slate-100">
                <tr>
                    <th scope="col" class="py-4 px-6 font-semibold w-12 text-center">No</th>
                    <th scope="col" class="py-4 px-6 font-semibold">Nama Kurir</th>
                    <th scope="col" class="py-4 px-6 font-semibold">No. Telepon</th>
                    <th scope="col" class="py-4 px-6 font-semibold">Plat Nomor Kendaraan</th>
                    <th scope="col" class="py-4 px-6 font-semibold text-center">Status</th>
                    <th scope="col" class="py-4 px-6 font-semibold text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($couriers as $courier)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="py-4 px-6 text-center">{{ $loop->iteration }}</td>
                    <td class="py-4 px-6 font-bold text-slate-800">{{ $courier->name }}</td>
                    <td class="py-4 px-6 text-slate-600">{{ $courier->phone }}</td>
                    <td class="py-4 px-6">
                        <span class="font-mono bg-slate-100 text-slate-700 py-1 px-2.5 rounded border border-slate-200">{{ strtoupper($courier->vehicle_number) }}</span>
                    </td>
                    <td class="py-4 px-6 text-center">
                        @if($courier->is_active)
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-600/20">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Aktif
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-red-50 px-2.5 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/10">
                                <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span> Nonaktif
                            </span>
                        @endif
                    </td>
                    <td class="py-4 px-6 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('admin.couriers.edit', $courier->id) }}" class="p-2 text-slate-400 hover:text-[#0a7b8c] bg-slate-50 hover:bg-cyan-50 rounded-lg transition"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></a>
                            <form action="{{ route('admin.couriers.destroy', $courier->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus kurir ini?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-2 text-slate-400 hover:text-red-600 bg-slate-50 hover:bg-red-50 rounded-lg transition"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="py-8 text-center text-slate-500">Belum ada data armada pengiriman.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection