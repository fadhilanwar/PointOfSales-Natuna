@extends('admin.layouts.app')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <h2 class="text-2xl font-bold text-slate-800">Tambah Pemasok</h2>
    <a href="{{ route('admin.suppliers.index') }}" class="text-sm font-medium text-slate-500 hover:text-[#0a7b8c]">Kembali</a>
</div>

<div class="rounded-xl border border-slate-200 bg-white shadow-sm p-6 max-w-2xl">
    <form action="{{ route('admin.suppliers.store') }}" method="POST">
        @csrf
        
        <div class="mb-5">
            <label class="mb-2 block text-sm font-semibold text-slate-800">Nama Perusahaan / Pabrik <span class="text-red-500">*</span></label>
            <input type="text" name="supplier_name" value="{{ old('supplier_name') }}" placeholder="Contoh: PT Pertamina (Persero)" class="w-full rounded-lg border border-slate-300 py-2.5 px-4 outline-none focus:border-[#0a7b8c]" required>
            @error('supplier_name')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
        </div>

        <div class="mb-5 grid grid-cols-1 md:grid-cols-1 gap-5">
            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-800">No. Telepon <span class="text-red-500">*</span></label>
                <input type="text" name="phone" value="{{ old('phone') }}" placeholder="Contoh: 022-1234567" class="w-full rounded-lg border border-slate-300 py-2.5 px-4 outline-none focus:border-[#0a7b8c]" required>
                @error('phone')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>
           
        </div>

        <div class="mb-6">
            <label class="mb-2 block text-sm font-semibold text-slate-800">Alamat Lengkap (Opsional)</label>
            <textarea name="address" rows="4" class="w-full rounded-lg border border-slate-300 py-2.5 px-4 outline-none focus:border-[#0a7b8c]">{{ old('address') }}</textarea>
        </div>

        <button type="submit" class="rounded-lg bg-[#0a7b8c] py-2.5 px-6 font-medium text-white hover:bg-[#075e6b] transition-all">Simpan Pemasok</button>
    </form>
</div>
@endsection