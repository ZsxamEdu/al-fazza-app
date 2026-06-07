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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    @stack('scripts')
</head>
<body>

<div class="flex h-screen font-sans overflow-hidden relative">
    <!-- Sidebar -->
    <div id="admin-sidebar" class="w-64 bg-dark-brown text-white p-5 flex flex-col shrink-0 fixed inset-y-0 left-0 transform -translate-x-full lg:relative lg:translate-x-0 transition duration-200 ease-in-out z-50">
        <div class="text-center pt-2.5">
            <img loading="lazy" src="{{ asset('assets/img/footer-logo.png') }}" alt="Al-Fazza Bakery" class="mx-auto max-w-full h-32 mb-2.5">
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

    <div class="flex-1 flex flex-col min-w-0 bg-bg-cream">
        <!-- Mobile Header -->
        <div class="lg:hidden flex flex-row-reverse items-center justify-end p-4 bg-dark-brown text-white shadow-md z-40">
            <h1 class="text-xl font-bold m-0 ml-4 tracking-wide">Admin Panel</h1>
            <button id="mobile-menu-btn" class="text-white hover:text-gray-300 focus:outline-none">
                <i class="fa-solid fa-bars text-2xl"></i>
            </button>
        </div>

        <!-- Overlay -->
        <div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-40 hidden lg:hidden"></div>

        <div class="flex-1 p-5 md:p-8 overflow-y-auto">
            @yield('content')
        </div>
    </div>
</div>

<script src="{{ asset('assets/js/script.js') }}?v={{ time() }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btn = document.getElementById('mobile-menu-btn');
        const sidebar = document.getElementById('admin-sidebar');
        const overlay = document.getElementById('sidebar-overlay');

        if(btn) {
            btn.addEventListener('click', () => {
                sidebar.classList.toggle('-translate-x-full');
                overlay.classList.toggle('hidden');
            });
        }
        
        if(overlay) {
            overlay.addEventListener('click', () => {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            });
        }
    });
</script>
</body>
</html>