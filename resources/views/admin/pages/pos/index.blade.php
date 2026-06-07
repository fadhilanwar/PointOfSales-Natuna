@extends('admin.layouts.app')

@section('content')
<div class="h-[calc(100vh-80px)] flex flex-col -m-6 p-6 bg-slate-100">
    
    @if(session('success'))
    <div class="mb-4 p-4 text-sm font-medium text-emerald-800 rounded-lg bg-emerald-50 border border-emerald-200 shadow-sm flex justify-between items-center">
        <span class="font-bold">{{ session('success') }}</span>
        
        @if(session('order_id'))
            <!-- Tombol Cetak Struk dengan target="_blank" -->
            <a href="{{ route('admin.pos.receipt', session('order_id')) }}" 
               target="_blank"
               class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-1.5 px-4 rounded-md shadow-sm transition-colors text-xs flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Cetak Struk
            </a>
        @endif
    </div>
    @endif

    @if(session('error'))
    <div class="mb-4 p-4 text-sm text-red-800 rounded-lg bg-red-50 border border-red-200 shadow-sm font-semibold">
        {{ session('error') }}
    </div>
    @endif

    <form id="pos-form" action="{{ route('admin.pos.store') }}" method="POST">
        @csrf
        <input type="hidden" name="invoice_number" value="{{ $invoice_number }}">
        <input type="hidden" name="grand_total" id="input-grand-total" value="{{ $grandTotal }}">
    </form>

    <div class="flex-1 grid grid-cols-1 lg:grid-cols-12 gap-6 min-h-0">
        
        <div class="lg:col-span-8 flex flex-col h-full bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-4 border-b border-slate-100 bg-white">
                <div class="relative w-full">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" id="search-product" class="bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-lg focus:ring-[#0a7b8c] focus:border-[#0a7b8c] block w-full pl-10 p-3 outline-none" placeholder="Ketik nama produk atau scan barcode...">
                </div>
            </div>

            <div class="flex-1 p-4 overflow-y-auto bg-slate-50/50">
                <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4">
                    @foreach($products as $product)
                    <div class="product-card bg-white rounded-xl border border-slate-200 hover:border-slate-300 p-3 flex flex-col shadow-sm transition-all" 
                         data-name="{{ strtolower($product->name) }}" data-barcode="{{ strtolower($product->barcode ?? '') }}">
                        
                        <div class="h-24 w-full bg-slate-100 rounded-lg mb-3 overflow-hidden flex items-center justify-center">
                            @if($product->image_path)
                                <img src="{{ asset('storage/'.$product->image_path) }}" class="w-full h-full object-cover">
                            @else
                                <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            @endif
                        </div>

                        <h3 class="font-bold text-slate-800 text-sm leading-tight mb-1 line-clamp-2" title="{{ $product->name }}">{{ $product->name }}</h3>
                        <div class="mt-auto mb-3 flex justify-between items-end">
                            <span class="font-bold text-[#0a7b8c] text-sm">Rp {{ number_format($product->base_price, 0, ',', '.') }}</span>
                            <span class="text-xs font-semibold px-2 py-0.5 rounded {{ $product->stock < $product->low_stock_threshold ? 'bg-red-100 text-red-600' : 'bg-slate-100 text-slate-500' }}">Stok: {{ $product->stock }}</span>
                        </div>

                        <form action="{{ route('admin.pos.cart.add') }}" method="POST" class="m-0">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <button type="submit" onclick="return confirm('Masukan {{ $product->name }} kedalam Keranjang?')" class="w-full bg-slate-50 hover:bg-[#0a7b8c] border border-slate-200 hover:border-[#0a7b8c] text-slate-600 hover:text-white font-bold py-2 rounded-lg text-sm transition-all shadow-sm">
                                Tambah
                            </button>
                        </form>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="lg:col-span-4 bg-white rounded-xl shadow-sm border border-slate-200 flex flex-col h-full">
            
            <div class="p-4 border-b border-slate-100 flex justify-between items-center bg-slate-50 rounded-t-xl">
                <h3 class="font-bold text-slate-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#0a7b8c]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    Keranjang Belanja
                </h3>
                <span class="text-xs font-bold px-2.5 py-1 bg-white border border-slate-200 rounded-md text-slate-500">{{ $invoice_number }}</span>
            </div>

            

            <div class="flex-1 overflow-y-auto p-3 bg-slate-50/30">
                @forelse($cartItems as $item)
                <div class="flex justify-between items-center p-3 mb-3 bg-white border border-slate-100 rounded-xl shadow-sm hover:border-[#0a7b8c]/30 transition-colors">
                    
                    <div class="flex-1 min-w-0 pr-3">
                        <h4 class="font-bold text-sm text-slate-800 truncate" title="{{ $item->product->name }}">{{ $item->product->name }}</h4>
                        <div class="text-[#0a7b8c] font-bold text-xs mt-1">Rp {{ number_format($item->product->base_price, 0, ',', '.') }}</div>
                    </div>
                    
                    <div class="flex items-center gap-1.5">
                        <form action="{{ route('admin.pos.cart.update') }}" method="POST" class="m-0">
                            @csrf
                            <input type="hidden" name="cart_item_id" value="{{ $item->id }}">
                            <input type="hidden" name="action" value="minus">
                            <button type="submit" class="w-7 h-7 flex items-center justify-center bg-slate-100 border border-slate-200 rounded-md text-slate-600 font-bold hover:bg-slate-200 transition-colors">-</button>
                        </form>

                        <form action="{{ route('admin.pos.cart.update') }}" method="POST" class="m-0">
                            @csrf
                            <input type="hidden" name="cart_item_id" value="{{ $item->id }}">
                            <input type="number" name="quantity" value="{{ $item->quantity }}" min="0" max="{{ $item->product->stock }}" 
                                   title="Ketik angka lalu tekan Enter untuk menyimpan"
                                   class="w-12 text-center font-bold text-slate-700 bg-slate-50 border border-slate-200 rounded-md py-1 px-1 outline-none focus:border-[#0a7b8c] focus:bg-white transition-all">
                        </form>

                        <form action="{{ route('admin.pos.cart.update') }}" method="POST" class="m-0">
                            @csrf
                            <input type="hidden" name="cart_item_id" value="{{ $item->id }}">
                            <input type="hidden" name="action" value="plus">
                            <button type="submit" class="w-7 h-7 flex items-center justify-center bg-slate-100 border border-slate-200 rounded-md text-slate-600 font-bold hover:bg-slate-200 transition-colors">+</button>
                        </form>

                        <form action="{{ route('admin.pos.cart.update') }}" method="POST" class="m-0 ml-1">
                            @csrf
                            <input type="hidden" name="cart_item_id" value="{{ $item->id }}">
                            <input type="hidden" name="quantity" value="0">
                            <button onclick="return confirm('Yakin menghapus barang {{ $item->product->name }} dari Keranjang Belanja.')" type="submit" class="text-red-400 hover:text-red-600 p-1.5 bg-red-50 hover:bg-red-100 rounded-md transition-colors" title="Hapus dari Keranjang">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="h-full flex flex-col items-center justify-center text-slate-400 opacity-80">
                    <svg class="w-16 h-16 mb-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    <span class="text-sm font-medium">Keranjang masih kosong</span>
                </div>
                @endforelse
            </div>

            <div class="border-t border-slate-200 bg-white p-5 rounded-b-xl space-y-5 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)] z-10 relative">
                
                <div class="flex justify-between items-end border-b border-slate-100 pb-4">
                    <span class="font-bold text-slate-500 uppercase tracking-wider text-xs">Total Belanja</span>
                    <span class="text-3xl font-extrabold text-[#0a7b8c] leading-none">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-1.5">Metode Bayar</label>
                        <select name="payment_method" form="pos-form" class="w-full rounded-lg border border-slate-300 bg-slate-50 py-2.5 px-3 text-sm font-semibold text-slate-700 outline-none focus:border-[#0a7b8c] focus:bg-white transition-colors">
                            <option value="cod">Tunai (Cash)</option>
                            <option value="transfer">Transfer Bank</option>
                            <option value="hutang">Hutang / Tempo</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-1.5">Uang Diterima (Rp)</label>
                        <input type="number" name="amount_paid" id="amount-paid" form="pos-form" class="w-full rounded-lg border border-slate-300 bg-slate-50 py-2.5 px-3 text-sm font-bold text-slate-800 outline-none focus:border-[#0a7b8c] focus:bg-white transition-colors placeholder:font-normal placeholder:text-slate-400" placeholder="Ketik nominal..." required {{ $cartItems->count() == 0 ? 'disabled' : '' }}>
                    </div>
                </div>

                <div class="p-3 border-b border-slate-100 bg-white">
                        <label class="block text-xs font-bold text-slate-600 mb-1.5">Nama Pembeli (wajib jika hutang)</label>

                <select name="user_id" form="pos-form" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-lg focus:border-[#0a7b8c] p-2.5 outline-none font-medium transition-colors">
                    <option value="">-- Pilih Pelanggan (Wajib) --</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->name }} {{ $customer->shop_name ? '('.$customer->shop_name.')' : '' }}</option>
                    @endforeach
                </select>
            </div>

                <div class="flex justify-between items-center pt-2">
                    <span class="font-bold text-slate-500 text-sm">Kembalian</span>
                    <span class="text-xl font-bold text-slate-800" id="display-change">Rp 0</span>
                </div>

                <button type="submit" form="pos-form" class="w-full bg-[#0a7b8c] cursor-pointer text-white font-bold py-3.5 rounded-xl shadow-lg shadow-cyan-500/30 hover:bg-[#075e6b] hover:shadow-cyan-500/50 transition-all disabled:opacity-50 disabled:cursor-not-allowed uppercase tracking-wider text-sm flex items-center justify-center gap-2" {{ $cartItems->count() == 0 ? 'disabled' : '' }}>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    Buat Transaksi
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Fungsi format ke Rupiah
    function formatRupiah(number) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number);
    }

    // Fungsi menghitung kembalian secara Real-Time (UI Only)
    function calculateChange() {
        const grandTotal = parseInt(document.getElementById('input-grand-total').value) || 0;
        const amountPaid = parseInt(document.getElementById('amount-paid').value) || 0;
        const change = amountPaid - grandTotal;
        
        const displayChange = document.getElementById('display-change');
        if (change >= 0 && grandTotal > 0 && amountPaid > 0) {
            displayChange.innerText = formatRupiah(change);
            displayChange.className = "text-xl font-bold text-emerald-600";
        } else if (amountPaid > 0 && change < 0) {
            displayChange.innerText = "Kurang " + formatRupiah(Math.abs(change));
            displayChange.className = "text-xl font-bold text-red-500";
        } else {
            displayChange.innerText = 'Rp 0';
            displayChange.className = "text-xl font-bold text-slate-800";
        }
    }

    // Event listener untuk textfield uang diterima
    const amountPaidInput = document.getElementById('amount-paid');
    if(amountPaidInput) {
        amountPaidInput.addEventListener('input', calculateChange);
    }

    // Filter Pencarian Etalase Produk (Bisa scan barcode atau ketik nama)
    document.getElementById('search-product').addEventListener('input', function(e) {
        const term = e.target.value.toLowerCase();
        document.querySelectorAll('.product-card').forEach(card => {
            const name = card.getAttribute('data-name');
            const barcode = card.getAttribute('data-barcode');
            card.style.display = (name.includes(term) || barcode.includes(term)) ? 'flex' : 'none';
        });
    });
</script>
@endsection