@extends('admin.layouts.app')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <h2 class="text-2xl font-bold text-slate-800">Edit Armada</h2>
    <a href="{{ route('admin.couriers.index') }}" class="text-sm font-medium text-slate-500 hover:text-[#0a7b8c]">Kembali</a>
</div>

<div class="rounded-xl border border-slate-200 bg-white shadow-sm p-6 max-w-2xl">
    <form action="{{ route('admin.couriers.update', $courier->id) }}" method="POST">
        @csrf @method('PUT')
        
        <div class="mb-5">
            <label class="mb-2 block text-sm font-semibold text-slate-800">Nama Kurir / Supir <span class="text-red-500">*</span></label>
            <input type="text" name="name" value="{{ old('name', $courier->name) }}" class="w-full rounded-lg border border-slate-300 py-2.5 px-4 outline-none focus:border-[#0a7b8c]" required>
            @error('name')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
        </div>

        <div class="mb-5 grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-800">No. Telepon / WhatsApp <span class="text-red-500">*</span></label>
                <input type="text" name="phone" value="{{ old('phone', $courier->phone) }}" class="w-full rounded-lg border border-slate-300 py-2.5 px-4 outline-none focus:border-[#0a7b8c]" required>
                @error('phone')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-800">Plat Nomor Kendaraan <span class="text-red-500">*</span></label>
                <input type="text" name="vehicle_number" value="{{ old('vehicle_number', $courier->vehicle_number) }}" class="w-full rounded-lg border border-slate-300 py-2.5 px-4 outline-none focus:border-[#0a7b8c]" required>
                @error('vehicle_number')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="mb-6 flex items-center mt-6 p-4 rounded-lg bg-slate-50 border border-slate-100">
            <input id="is_active" type="checkbox" name="is_active" class="w-5 h-5 text-[#0a7b8c] bg-white border-slate-300 rounded focus:ring-[#0a7b8c]" 
                   {{ old('is_active', $courier->is_active) ? 'checked' : '' }}>
            <label for="is_active" class="ml-3 text-sm font-medium text-slate-700 cursor-pointer">
                Armada Aktif & Siap Beroperasi
            </label>
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="rounded-lg bg-[#0a7b8c] py-2.5 px-6 font-medium text-white hover:bg-[#075e6b] transition-all">Perbarui Data</button>
            <a href="{{ route('admin.couriers.index') }}" class="rounded-lg bg-slate-100 py-2.5 px-6 font-medium text-slate-600 hover:bg-slate-200 transition-all">Batal</a>
        </div>
    </form>
</div>
@endsection