<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Natuna Grosir - Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
@import url('https://fonts.googleapis.com/css2?family=Chewy&family=Figtree:ital,wght@0,300..900;1,300..900&family=Google+Sans:ital,opsz,wght@0,17..18,400..700;1,17..18,400..700&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap');
</style>
    
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
    @stack('script')
</body>
</html>