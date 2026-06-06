@extends('admin.layouts.app')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <h2 class="text-2xl font-bold text-slate-800">Tambah Armada</h2>
    <a href="{{ route('admin.couriers.index') }}" class="text-sm font-medium text-slate-500 hover:text-[#0a7b8c]">Kembali</a>
</div>

<div class="rounded-xl border border-slate-200 bg-white shadow-sm p-6 max-w-2xl">
    <form action="{{ route('admin.couriers.store') }}" method="POST">
        @csrf
        
        <div class="mb-5">
            <label class="mb-2 block text-sm font-semibold text-slate-800">Nama Kurir / Supir <span class="text-red-500">*</span></label>
            <input type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: Budi Santoso" class="w-full rounded-lg border border-slate-300 py-2.5 px-4 outline-none focus:border-[#0a7b8c]" required>
            @error('name')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
        </div>

        <div class="mb-5 grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-800">No. Telepon / WhatsApp <span class="text-red-500">*</span></label>
                <input type="text" name="phone" value="{{ old('phone') }}" placeholder="Contoh: 081234567890" class="w-full rounded-lg border border-slate-300 py-2.5 px-4 outline-none focus:border-[#0a7b8c]" required>
                @error('phone')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-800">Plat Nomor Kendaraan <span class="text-red-500">*</span></label>
                <input type="text" name="vehicle_number" value="{{ old('vehicle_number') }}" placeholder="Contoh: D 1234 ABC" class="w-full rounded-lg border border-slate-300 py-2.5 px-4 outline-none focus:border-[#0a7b8c]" required>
                @error('vehicle_number')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>
        </div>

       {{-- Status otomatis diset aktif di belakang layar --}}
        <input type="hidden" name="is_active" value="1">

        <div class="flex items-center gap-4">
            <button type="submit" class="rounded-lg bg-[#0a7b8c] py-2.5 px-6 font-medium text-white hover:bg-[#075e6b] transition-all">Simpan Data</button>
            <a href="{{ route('admin.couriers.index') }}" class="rounded-lg bg-slate-100 py-2.5 px-6 font-medium text-slate-600 hover:bg-slate-200 transition-all">Batal</a>
        </div>
    </form>
</div>
@endsection