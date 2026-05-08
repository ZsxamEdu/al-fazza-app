<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Admin Al-Fazza</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    
    @stack('scripts')
</head>
<body>

<div class="admin-layout">
    <div class="sidebar">
        <h2><i class="fa-solid fa-shop"></i> Admin Panel</h2>
        
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fa-solid fa-house"></i> Dashboard
        </a>
        <a href="{{ route('admin.produk.index') }}" class="{{ request()->routeIs('admin.produk.*') ? 'active' : '' }}">
            <i class="fa-solid fa-bread-slice"></i> Kelola Produk
        </a>
        <a href="{{ route('admin.stok.index') }}" class="{{ request()->routeIs('admin.stok.*') ? 'active' : '' }}">
            <i class="fa-solid fa-boxes-stacked"></i> Kelola Stok
        </a>
        <a href="{{ route('admin.laporan.index') }}" class="{{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}">
            <i class="fa-solid fa-chart-line"></i> Laporan Keuangan
        </a>

        
        <br>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn-logout"><i class="fa-solid fa-right-from-bracket"></i> Keluar</button>
        </form>
    </div>

    <div class="main-content">
        @yield('content')
    </div>
</div>

<script src="{{ asset('assets/js/script.js') }}"></script>
</body>
</html>