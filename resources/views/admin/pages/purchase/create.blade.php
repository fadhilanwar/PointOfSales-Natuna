@extends('admin.layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    /* Menyesuaikan Select2 agar bentuknya mirip dengan input Tailwind */
    .select2-container .select2-selection--single {
        height: 42px !important;
        border-color: #cbd5e1 !important;
        border-radius: 0.5rem !important;
        display: flex;
        align-items: center;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #334155 !important;
        padding-left: 0.75rem !important;
        font-size: 0.875rem !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 40px !important;
        right: 8px !important;
    }
    .select2-container--default .select2-selection--single:focus {
        border-color: #0a7b8c !important;
        outline: none !important;
    }
</style>

<div class="mb-6 flex items-center justify-between">
    <h2 class="text-2xl font-bold text-slate-800">Buat Transaksi Pembelian</h2>
    <a href="{{ route('admin.purchases.index') }}" class="text-sm font-medium text-slate-500 hover:text-[#0a7b8c]">Kembali</a>
</div>

<form action="{{ route('admin.purchases.store') }}" method="POST">
    @csrf
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="lg:col-span-1 space-y-6">
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <h3 class="font-bold text-slate-800 mb-4 border-b pb-2">Informasi Umum</h3>
                
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-slate-700 mb-1">No. Invoice</label>
                    <input type="text" name="invoice_number" value="{{ $invoice_number }}" class="w-full bg-slate-50 rounded-lg border border-slate-300 py-2.5 px-4 text-sm font-bold text-slate-600 outline-none" readonly>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Supplier <span class="text-red-500">*</span></label>
                    <select name="supplier_id" class="searchable-select w-full" required>
                        <option value="">-- Ketik / Pilih Supplier --</option>
                        @foreach($suppliers as $sup)
                            <option value="{{ $sup->id }}">{{ $sup->supplier_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Catatan Tambahan</label>
                    <textarea name="notes" rows="3" class="w-full rounded-lg border border-slate-300 py-2.5 px-4 text-sm outline-none focus:border-[#0a7b8c]" placeholder="Catatan pengiriman..."></textarea>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="rounded-xl border border-slate-200 bg-white shadow-sm flex flex-col h-full">
                <div class="p-4 border-b border-slate-100 flex justify-between items-center bg-slate-50 rounded-t-xl">
                    <h3 class="font-bold text-slate-800">Daftar Barang Masuk</h3>
                    <button type="button" id="btn-add-item" class="text-sm bg-white border border-slate-300 text-slate-700 hover:text-[#0a7b8c] hover:border-[#0a7b8c] py-1.5 px-3 rounded-md font-semibold transition-all shadow-sm">+ Tambah Baris</button>
                </div>

                <div class="p-0 overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-slate-50 border-b border-slate-200 text-slate-500">
                            <tr>
                                <th class="p-3 w-5/12 font-semibold">Produk</th>
                                <th class="p-3 w-2/12 font-semibold">Qty</th>
                                <th class="p-3 w-3/12 font-semibold">Harga Modal</th>
                                <th class="p-3 w-2/12 font-semibold text-right">Subtotal</th>
                                <th class="p-3 w-12 text-center"></th>
                            </tr>
                        </thead>
                        <tbody id="items-container" class="divide-y divide-slate-100">
                            <tr class="item-row">
                                <td class="p-3">
                                    <select name="product_id[]" class="searchable-select product-select w-full" required>
                                        <option value="">Ketik nama produk...</option>
                                        @foreach($products as $prod)
                                            <option value="{{ $prod->id }}" data-price="{{ $prod->cost_price }}">{{ $prod->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="p-3">
                                    <input type="number" name="quantity[]" value="1" min="1" class="qty-input w-full rounded border border-slate-300 py-2.5 px-2 text-center outline-none focus:border-[#0a7b8c]" required>
                                </td>
                                <td class="p-3">
                                    <input type="number" name="cost_price[]" class="price-input w-full rounded border border-slate-300 py-2.5 px-2 outline-none focus:border-[#0a7b8c]" required>
                                </td>
                                <td class="p-3 text-right font-semibold text-slate-700 subtotal-text">Rp 0</td>
                                <td class="p-3 text-center">
                                    <button type="button" class="btn-remove text-red-400 hover:text-red-600 hidden">
                                        <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot class="bg-slate-50 border-t border-slate-200">
                            <tr>
                                <td colspan="3" class="p-4 text-right font-bold text-slate-600 uppercase tracking-wider">Grand Total</td>
                                <td colspan="2" class="p-4 text-right font-bold text-xl text-[#0a7b8c]" id="grand-total-text">Rp 0</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                
                <div class="p-4 border-t border-slate-100 mt-auto flex justify-end">
                    <button type="submit" class="bg-[#0a7b8c] text-white font-bold py-3 px-8 rounded-lg hover:bg-[#075e6b] shadow-sm transition-all">Simpan Transaksi</button>
                </div>
            </div>
        </div>
    </div>
</form>

<template id="row-template">
    <tr class="item-row">
        <td class="p-3">
            <select name="product_id[]" class="searchable-select product-select w-full" required>
                <option value="">Ketik nama produk...</option>
                @foreach($products as $prod)
                    <option value="{{ $prod->id }}" data-price="{{ $prod->cost_price }}">{{ $prod->name }}</option>
                @endforeach
            </select>
        </td>
        <td class="p-3">
            <input type="number" name="quantity[]" value="1" min="1" class="qty-input w-full rounded border border-slate-300 py-2.5 px-2 text-center outline-none focus:border-[#0a7b8c]" required>
        </td>
        <td class="p-3">
            <input type="number" name="cost_price[]" class="price-input w-full rounded border border-slate-300 py-2.5 px-2 outline-none focus:border-[#0a7b8c]" required>
        </td>
        <td class="p-3 text-right font-semibold text-slate-700 subtotal-text">Rp 0</td>
        <td class="p-3 text-center">
            <button type="button" class="btn-remove text-red-400 hover:text-red-600">
                <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
            </button>
        </td>
    </tr>
</template>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    
    // Inisialisasi Select2 pada saat halaman pertama dimuat
    function initSelect2() {
        $('.searchable-select').select2({
            width: '100%' // Agar responsif mengikuti lebar kolom
        });
    }
    
    // Panggil inisialisasi pertama
    initSelect2();

    // Fungsi Format Rupiah
    function formatRupiah(number) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number);
    }

    // Fungsi Hitung Total
    function calculateTotal() {
        let grandTotal = 0;
        $('.item-row').each(function() {
            let qty = parseFloat($(this).find('.qty-input').val()) || 0;
            let price = parseFloat($(this).find('.price-input').val()) || 0;
            let subtotal = qty * price;
            
            $(this).find('.subtotal-text').text(formatRupiah(subtotal));
            grandTotal += subtotal;
        });
        $('#grand-total-text').text(formatRupiah(grandTotal));
    }

    // Event Listener saat produk dipilih dari Select2 (Ambil Harga Modal Otomatis)
    // Di Select2, kita harus mendengarkan event khusus 'select2:select'
    $(document).on('select2:select', '.product-select', function (e) {
        let price = $(this).find(':selected').data('price');
        $(this).closest('tr').find('.price-input').val(price || '');
        calculateTotal();
    });

    // Event Listener saat Qty atau Harga diubah manual
    $(document).on('input', '.qty-input, .price-input', function() {
        calculateTotal();
    });

    // Tambah Baris Baru
    $('#btn-add-item').click(function() {
        // Ambil HTML mentah dari Template
        let templateContent = $('#row-template').html();
        
        // Tambahkan ke Container
        $('#items-container').append(templateContent);
        
        // Penting: Inisialisasi ulang Select2 HANYA pada baris yang baru ditambahkan
        $('#items-container .item-row:last .searchable-select').select2({
            width: '100%'
        });
        
        calculateTotal();
    });

    // Hapus Baris
    $(document).on('click', '.btn-remove', function() {
        $(this).closest('tr').remove();
        calculateTotal();
    });
});
</script>
@endsection