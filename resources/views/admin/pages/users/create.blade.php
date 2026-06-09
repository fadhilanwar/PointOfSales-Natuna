@extends('admin.layouts.app')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <h2 class="text-2xl font-bold text-slate-800">Tambah Pengguna</h2>
    <a href="{{ request('redirect_to') == 'pos' ? route('admin.pos.index') : route('admin.users.index') }}" class="text-sm text-slate-500 hover:text-[#0a7b8c]">Kembali</a>
</div>

<div class="rounded-xl border border-slate-200 bg-white shadow-sm p-6 max-w-4xl">
    <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf
        @if(request('redirect_to'))
            <input type="hidden" name="redirect_to" value="{{ request('redirect_to') }}">
        @endif
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <h3 class="text-lg font-semibold text-slate-800 mb-4 border-b pb-2">Informasi Akun</h3>
                
                <div class="mb-4">
                    <label class="mb-2 block text-sm font-semibold text-slate-800">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" class="w-full rounded-lg border border-slate-300 px-4 py-2.5 outline-none focus:border-[#0a7b8c]" required>
                    @error('name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="mb-4">
                    <label class="mb-2 block text-sm font-semibold text-slate-800">Username <span class="text-red-500">*</span></label>
                    <input type="text" name="username" value="{{ old('username') }}" class="w-full rounded-lg border border-slate-300 px-4 py-2.5 outline-none focus:border-[#0a7b8c]" required>
                    @error('username')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="mb-4">
                    <label class="mb-2 block text-sm font-semibold text-slate-800">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" class="w-full rounded-lg border border-slate-300 px-4 py-2.5 outline-none focus:border-[#0a7b8c]" required>
                    @error('email')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="mb-4">
                    <label class="mb-2 block text-sm font-semibold text-slate-800">Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password" class="w-full rounded-lg border border-slate-300 px-4 py-2.5 outline-none focus:border-[#0a7b8c]" required>
                    @error('password')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="mb-4">
                    <label class="mb-2 block text-sm font-semibold text-slate-800">Role <span class="text-red-500">*</span></label>
                    <select name="role" class="w-full rounded-lg border border-slate-300 px-4 py-2.5 outline-none focus:border-[#0a7b8c]" required>
                        <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User / Pelanggan</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>
            </div>

            <div>
                <h3 class="text-lg font-semibold text-slate-800 mb-4 border-b pb-2">Informasi Toko/Kontak</h3>
                
                <div class="mb-4">
                    <label class="mb-2 block text-sm font-semibold text-slate-800">Nama Toko/Warung (Opsional)</label>
                    <input type="text" name="shop_name" value="{{ old('shop_name') }}" class="w-full rounded-lg border border-slate-300 px-4 py-2.5 outline-none focus:border-[#0a7b8c]">
                </div>

                <div class="mb-4">
                    <label class="mb-2 block text-sm font-semibold text-slate-800">No. HP (Opsional)</label>
                    <input type="text" name="phone_number" value="{{ old('phone_number') }}" class="w-full rounded-lg border border-slate-300 px-4 py-2.5 outline-none focus:border-[#0a7b8c]">
                </div>

                <div class="mb-4">
                    <label class="mb-2 block text-sm font-semibold text-slate-800">Alamat Lengkap (Opsional)</label>
                    <textarea name="address" rows="5" class="w-full rounded-lg border border-slate-300 px-4 py-2.5 outline-none focus:border-[#0a7b8c]">{{ old('address') }}</textarea>
                </div>
            </div>
        </div>

        <button type="submit" class="rounded-lg bg-[#0a7b8c] py-2.5 px-6 font-medium text-white hover:bg-[#075e6b] transition-all">Simpan Pengguna</button>
    </form>
</div>
@endsection