<aside class="absolute left-0 top-0 z-9999 flex h-screen w-72 flex-col overflow-y-hidden bg-white shadow-md duration-300 ease-linear lg:static lg:translate-x-0">
    <div class="flex items-center justify-center py-6 px-6 border-b border-slate-100">
        <h1 class="text-2xl font-bold text-[#0a7b8c]">Natuna Grosir</h1>
    </div>

    <div class="flex flex-col overflow-y-auto duration-300 ease-linear">
        <nav class="mt-5 py-4 px-4 lg:mt-9 lg:px-6">
            <ul class="mb-6 flex flex-col gap-1.5">
                <li>
                    <a href="{{ route('admin.dashboard') }}" 
                       class="group relative flex items-center gap-2.5 rounded-sm py-2 px-4 font-medium duration-300 ease-in-out {{ request()->routeIs('admin.dashboard') ? 'bg-[#0a7b8c] text-white hover:bg-[#075e6b]' : 'text-slate-600 hover:bg-slate-100 hover:text-[#0a7b8c]' }}">
                        Dashboard
                    </a>
                </li>
                
                <h3 class="mb-4 ml-4 mt-4 text-sm font-semibold text-slate-400">DATA MASTER</h3>
                
                <li>
                    <a href="{{ route('admin.users.index') }}" 
                       class="group relative flex items-center gap-2.5 rounded-sm py-2 px-4 font-medium duration-300 ease-in-out {{ request()->routeIs('admin.users.*') ? 'bg-[#0a7b8c] text-white hover:bg-[#075e6b]' : 'text-slate-600 hover:bg-slate-100 hover:text-[#0a7b8c]' }}">
                        Data Users
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.categories.index') }}" 
                       class="group relative flex items-center gap-2.5 rounded-sm py-2 px-4 font-medium duration-300 ease-in-out {{ request()->routeIs('admin.categories.*') ? 'bg-[#0a7b8c] text-white hover:bg-[#075e6b]' : 'text-slate-600 hover:bg-slate-100 hover:text-[#0a7b8c]' }}">
                        Data Kategori
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.products.index') }}" 
                       class="group relative flex items-center gap-2.5 rounded-sm py-2 px-4 font-medium duration-300 ease-in-out {{ request()->routeIs('admin.products.*') ? 'bg-[#0a7b8c] text-white hover:bg-[#075e6b]' : 'text-slate-600 hover:bg-slate-100 hover:text-[#0a7b8c]' }}">
                        Data Produk
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>