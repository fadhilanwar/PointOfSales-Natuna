<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<<<<<<< HEAD
    <title>@yield('title', 'Admin - Natuna Grosir')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 font-sans antialiased">

    <div class="flex h-screen overflow-hidden">

        <aside class="w-64 bg-slate-900 text-white flex flex-col hidden md:flex">
            <div class="flex items-center justify-center h-16 border-b border-slate-700">
                <h1 class="text-xl font-bold uppercase tracking-wider text-indigo-400">Natuna Grosir</h1>
            </div>

            <div class="overflow-y-auto overflow-x-hidden flex-1">
                <ul class="flex flex-col py-4 space-y-1">
                    <li class="px-5 text-xs uppercase text-slate-400 font-semibold mb-2 mt-4">Menu Utama</li>
                    
                    <li>
                        <a href="#" class="relative flex flex-row items-center h-11 focus:outline-none hover:bg-slate-800 text-white border-l-4 border-transparent hover:border-indigo-500 pr-6">
                            <span class="inline-flex justify-center items-center ml-4"><i class="fas fa-tachometer-alt"></i></span>
                            <span class="ml-2 text-sm tracking-wide truncate">Dashboard</span>
                        </a>
                    </li>

                    <li class="px-5 text-xs uppercase text-slate-400 font-semibold mb-2 mt-4">Master Data</li>
                    <li>
                        <a href="#" class="relative flex flex-row items-center h-11 focus:outline-none hover:bg-slate-800 text-slate-300 hover:text-white border-l-4 border-transparent hover:border-indigo-500 pr-6">
                            <span class="inline-flex justify-center items-center ml-4"><i class="fas fa-tags"></i></span>
                            <span class="ml-2 text-sm tracking-wide truncate">Kategori</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="relative flex flex-row items-center h-11 focus:outline-none hover:bg-slate-800 text-slate-300 hover:text-white border-l-4 border-transparent hover:border-indigo-500 pr-6">
                            <span class="inline-flex justify-center items-center ml-4"><i class="fas fa-truck-loading"></i></span>
                            <span class="ml-2 text-sm tracking-wide truncate">Supplier</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="relative flex flex-row items-center h-11 focus:outline-none hover:bg-slate-800 text-slate-300 hover:text-white border-l-4 border-transparent hover:border-indigo-500 pr-6">
                            <span class="inline-flex justify-center items-center ml-4"><i class="fas fa-box"></i></span>
                            <span class="ml-2 text-sm tracking-wide truncate">Produk</span>
                        </a>
                    </li>

                    <li class="px-5 text-xs uppercase text-slate-400 font-semibold mb-2 mt-4">Transaksi</li>
                    <li>
                        <a href="#" class="relative flex flex-row items-center h-11 focus:outline-none hover:bg-slate-800 text-slate-300 hover:text-white border-l-4 border-transparent hover:border-emerald-500 pr-6">
                            <span class="inline-flex justify-center items-center ml-4"><i class="fas fa-shopping-cart text-emerald-400"></i></span>
                            <span class="ml-2 text-sm tracking-wide truncate">Kasir / Penjualan</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="relative flex flex-row items-center h-11 focus:outline-none hover:bg-slate-800 text-slate-300 hover:text-white border-l-4 border-transparent hover:border-indigo-500 pr-6">
                            <span class="inline-flex justify-center items-center ml-4"><i class="fas fa-file-invoice-dollar"></i></span>
                            <span class="ml-2 text-sm tracking-wide truncate">Restock Pembelian</span>
                        </a>
                    </li>
                </ul>
            </div>
            
            <div class="border-t border-slate-700 p-4">
                <form method="POST" action="#">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center text-sm text-red-400 hover:text-red-300 transition-colors">
                        <i class="fas fa-sign-out-alt mr-2"></i> Keluar
                    </button>
                </form>
            </div>
        </aside>

        <div class="flex flex-col flex-1 w-full overflow-y-auto overflow-x-hidden bg-gray-50">
            
            <header class="flex items-center justify-between px-6 py-4 bg-white border-b shadow-sm">
                <div class="flex items-center">
                    <button class="text-gray-500 focus:outline-none md:hidden">
                        <i class="fas fa-bars fa-lg"></i>
                    </button>
                    <h2 class="text-xl font-semibold text-gray-800 ml-4">@yield('header_title', 'Overview')</h2>
                </div>
                
                <div class="flex items-center">
                    <span class="text-sm font-medium text-gray-700 mr-2">Halo, Admin</span>
                    <div class="w-9 h-9 rounded-full bg-indigo-500 flex items-center justify-center text-white font-bold shadow">
                        A
                    </div>
                </div>
            </header>

            <main class="flex-1 p-6">
                @yield('content')
            </main>
            
        </div>
    </div>

=======
    <title>Admin Dashboard - Natuna Grosir</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased">
    <div class="flex h-screen overflow-hidden">
        
        @include('admin.components.sidebar')

        <div class="relative flex flex-col flex-1 overflow-y-auto overflow-x-hidden">
            
            @include('admin.components.header')

            <main class="w-full max-w-screen-2xl mx-auto p-4 md:p-6 2xl:p-10">
                @yield('content')
            </main>

        </div>
    </div>
>>>>>>> 543f66b574ae7bde4a097585e87f215412ada6f7
</body>
</html>