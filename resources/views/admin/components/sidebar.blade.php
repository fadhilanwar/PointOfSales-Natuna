<aside
    class="absolute left-0 top-0 z-9999 flex h-screen w-72 flex-col overflow-y-hidden bg-white shadow-md duration-300 ease-linear lg:static lg:translate-x-0">
    <div class="flex items-center justify-center py-6 px-6 border-b border-slate-100">
        <h1 class="text-2xl font-bold text-[#0a7b8c]">Natuna Grosir</h1>
    </div>

    <div class="flex flex-col overflow-y-auto duration-300 ease-linear">
        <nav class="mt-5 py-4 px-4 lg:mt-9 lg:px-6">
            <ul class="mb-6 flex flex-col gap-1.5">

                <li>
                    <a href="{{ route('admin.dashboard') }}"
                        class="group relative flex items-center gap-2.5 rounded-sm py-2 px-4 font-medium duration-300 ease-in-out {{ request()->routeIs('admin.dashboard') ? 'bg-[#0a7b8c] text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                        <img src="{{ asset('assets/icons/dashboard.png') }}"
                            class="w-5 h-5 {{ request()->routeIs('admin.dashboard') ? 'brightness-0 invert' : '' }}"
                            alt="icon">
                        Dashboard
                    </a>
                </li>

                <h3 class="mb-4 ml-4 mt-6 text-xs font-semibold text-slate-400 uppercase">Data Master</h3>
                <li><a href="{{ route('admin.users.index') }}"
                        class="group relative flex items-center gap-2.5 rounded-sm py-2 px-4 font-medium duration-300 ease-in-out {{ request()->routeIs('admin.users.*') ? 'bg-[#0a7b8c] text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                        <img src="{{ asset('assets/icons/user.png') }}"
                            class="w-5 h-5 {{ request()->routeIs('admin.users.*') ? 'brightness-0 invert' : '' }}"
                            alt="icon">Users</a></li>

                <li><a href="{{ route('admin.categories.index') }}"
                        class="group relative flex items-center gap-2.5 rounded-sm py-2 px-4 font-medium duration-300 ease-in-out {{ request()->routeIs('admin.categories.*') ? 'bg-[#0a7b8c] text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                        <img src="{{ asset('assets/icons/category.png') }}"
                            class="w-5 h-5 {{ request()->routeIs('admin.categories.*') ? 'brightness-0 invert' : '' }}"
                            alt="icon">Kategori</a></li>

                <li><a href="{{ route('admin.products.index') }}"
                        class="group relative flex items-center gap-2.5 rounded-sm py-2 px-4 font-medium duration-300 ease-in-out {{ request()->routeIs('admin.products.*') ? 'bg-[#0a7b8c] text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                        <img src="{{ asset('assets/icons/product.png') }}"
                            class="w-5 h-5 {{ request()->routeIs('admin.products.*') ? 'brightness-0 invert' : '' }}"
                            alt="icon">Produk</a></li>

                <li><a href="{{ route('admin.couriers.index') }}"
                        class="group relative flex items-center gap-2.5 rounded-sm py-2 px-4 font-medium duration-300 ease-in-out {{ request()->routeIs('admin.couriers.*') ? 'bg-[#0a7b8c] text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                        <img src="{{ asset('assets/icons/courier.png') }}"
                            class="w-5 h-5 {{ request()->routeIs('admin.couriers.*') ? 'brightness-0 invert' : '' }}"
                            alt="icon">Kurir</a></li>

                <li><a href="{{ route('admin.suppliers.index') }}"
                        class="group relative flex items-center gap-2.5 rounded-sm py-2 px-4 font-medium duration-300 ease-in-out {{ request()->routeIs('admin.suppliers.*') ? 'bg-[#0a7b8c] text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                        <img src="{{ asset('assets/icons/supplier.png') }}"
                            class="w-5 h-5 {{ request()->routeIs('admin.suppliers.*') ? 'brightness-0 invert' : '' }}"
                            alt="icon">Supplier</a></li>

                <h3 class="mb-4 ml-4 mt-6 text-xs font-semibold text-slate-400 uppercase">Transaksi</h3>
                <li><a href="{{ route('admin.purchases.index') }}"
                        class="group relative flex items-center gap-2.5 rounded-sm py-2 px-4 font-medium duration-300 ease-in-out {{ request()->routeIs('admin.purchases.*') ? 'bg-[#0a7b8c] text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                        <img src="{{ asset('assets/icons/supply.png') }}" class="w-5 h-5 {{ request()->routeIs('admin.purchases.*') ? 'brightness-0 invert' : '' }}" alt="icon">Suplai Barang</a>
                </li>
                <li><a href="#"
                        class="group relative flex items-center gap-2.5 rounded-sm py-2 px-4 font-medium duration-300 ease-in-out {{ request()->routeIs('admin.sales.*') ? 'bg-[#0a7b8c] text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                        <img src="{{ asset('assets/icons/cashier.png') }}" class="w-5 h-5 {{ request()->routeIs('admin.suppliers.*') ? 'brightness-0 invert' : '' }}" alt="icon">Penjualan</a>
                </li>

                <h3 class="mb-4 ml-4 mt-6 text-xs font-semibold text-slate-400 uppercase">Laporan</h3>
                <li><a href="#"
                        class="group relative flex items-center gap-2.5 rounded-sm py-2 px-4 font-medium duration-300 ease-in-out text-slate-600 hover:bg-slate-100">
                        <img src="{{ asset('assets/icons/report.png') }}" class="w-5 h-5" alt="icon">Laporan</a>
                </li>
                <li>
                    <form action="{{ route('logout') }}" method="POST"
                        onsubmit="return confirm('Apakah Anda yakin ingin keluar dari sistem?');">
                        @csrf
                        <button type="submit"
                            class="w-full group relative flex items-center gap-2.5 rounded-sm py-2 px-4 font-medium duration-300 ease-in-out text-red-600 hover:bg-red-50 transition-all">
                            <img src="{{ asset('assets/icons/out.png') }}" class="w-5 h-5" alt="icon">
                            Logout
                        </button>
                    </form>
                </li>
                <ul>

                </ul>
        </nav>
    </div>
</aside>
