<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Admin Al-Fazza</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/logo.png') }}">
    {{-- <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}"> --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    
    @stack('scripts')
</head>
<body>

<div class="flex min-h-screen font-sans">
    <div class="w-64 bg-dark-brown text-white p-5 flex flex-col shrink-0">
        <div class="text-center pt-2.5">
            <img src="{{ asset('assets/img/footer-logo.png') }}" alt="Al-Fazza Bakery" class="mx-auto max-w-full h-32 mb-2.5">
        </div>
        <h2 class="text-center mt-4 mb-6 tracking-[0.5px] border-b border-white/10 pb-4 text-2xl">Admin Panel</h2>
        
        <a href="{{ route('admin.dashboard') }}" class="block text-white no-underline p-3 mb-2.5 rounded transition-colors duration-300 hover:bg-primary-brown {{ request()->routeIs('admin.dashboard') ? 'bg-primary-brown' : '' }}">
            <i class="fa-solid fa-house w-6"></i> Dashboard
        </a>
        <a href="{{ route('admin.pesanan.index') }}" class="block text-white no-underline p-3 mb-2.5 rounded transition-colors duration-300 hover:bg-primary-brown {{ request()->routeIs('admin.pesanan.*') ? 'bg-primary-brown' : '' }}">
            <i class="fa-solid fa-clipboard-list w-6"></i> Kelola Pesanan
        </a>
        <a href="{{ route('admin.produk.index') }}" class="block text-white no-underline p-3 mb-2.5 rounded transition-colors duration-300 hover:bg-primary-brown {{ request()->routeIs('admin.produk.*') ? 'bg-primary-brown' : '' }}">
            <i class="fa-solid fa-bread-slice w-6"></i> Kelola Produk
        </a>
        <a href="{{ route('admin.stok.index') }}" class="block text-white no-underline p-3 mb-2.5 rounded transition-colors duration-300 hover:bg-primary-brown {{ request()->routeIs('admin.stok.*') ? 'bg-primary-brown' : '' }}">
            <i class="fa-solid fa-boxes-stacked w-6"></i> Kelola Stok
        </a>
        <a href="{{ route('admin.laporan.index') }}" class="block text-white no-underline p-3 mb-2.5 rounded transition-colors duration-300 hover:bg-primary-brown {{ request()->routeIs('admin.laporan.*') ? 'bg-primary-brown' : '' }}">
            <i class="fa-solid fa-chart-line w-6"></i> Laporan Keuangan
        </a>

        
        <br>
        <form action="{{ route('logout') }}" method="POST" >
            @csrf
            <button type="submit" class="w-full text-left bg-transparent border-none text-red-200 cursor-pointer p-3 text-base rounded transition-colors duration-300 hover:bg-red-500/10 hover:text-red-500"><i class="fa-solid fa-right-from-bracket w-6"></i> Keluar</button>
        </form>
    </div>

    <div class="flex-1 p-8 bg-bg-cream w-full overflow-y-auto">
        @yield('content')
    </div>
</div>

<script src="{{ asset('assets/js/script.js') }}"></script>
</body>
</html>