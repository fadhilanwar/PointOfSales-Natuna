@extends('admin.layouts.app')

@section('content')
<div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h2 class="text-2xl font-bold text-slate-800">Data Pemasok (Supplier)</h2>
        <p class="text-sm text-slate-500 mt-1">Kelola daftar pabrik atau distributor yang menyuplai barang.</p>
    </div>
    <a href="{{ route('admin.suppliers.create') }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-[#0a7b8c] py-2.5 px-5 text-sm font-semibold text-white hover:bg-[#075e6b] transition-all">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Tambah Pemasok
    </a>
</div>

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
                    <th scope="col" class="py-4 px-6 font-semibold">Nama Pemasok / Pabrik</th>
                    <th scope="col" class="py-4 px-6 font-semibold">Kontak & Telepon</th>
                    <th scope="col" class="py-4 px-6 font-semibold">Alamat Lengkap</th>
                    <th scope="col" class="py-4 px-6 font-semibold text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($suppliers as $supplier)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="py-4 px-6 text-center">{{ $loop->iteration }}</td>
                    <td class="py-4 px-6 font-bold text-slate-800">{{ $supplier->supplier_name }}</td>
                    <td class="py-4 px-6">
                        <p class="text-slate-800 font-medium">{{ $supplier->phone }}</p>
                    </td>
                    <td class="py-4 px-6 max-w-xs truncate" title="{{ $supplier->address }}">
                        {{ $supplier->address ?? 'Alamat belum diisi' }}
                    </td>
                    <td class="py-4 px-6 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('admin.suppliers.edit', $supplier->id) }}" class="p-2 text-slate-400 hover:text-[#0a7b8c] bg-slate-50 hover:bg-cyan-50 rounded-lg transition"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></a>
                            <form action="{{ route('admin.suppliers.destroy', $supplier->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus pemasok ini?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-2 text-slate-400 hover:text-red-600 bg-slate-50 hover:bg-red-50 rounded-lg transition"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="py-8 text-center text-slate-500">Belum ada data pemasok.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection