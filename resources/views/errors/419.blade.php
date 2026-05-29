@extends('layouts.error')
@section('title', '419 Sesi Berakhir')
@section('content')
    <i class="fa-solid fa-hourglass-end text-7xl text-primary-brown mb-6"></i>
    <h1 class="text-6xl font-black text-dark-brown mb-2">419</h1>
    <h2 class="text-2xl font-bold text-text-dark mb-4">Sesi Telah Habis</h2>
    <p class="text-text-medium mb-8">Halaman ini terlalu lama didiamkan sehingga sesi keamanan Anda telah kedaluwarsa. Silakan muat ulang halaman ini.</p>
    <button onclick="window.location.reload()" class="inline-block px-8 py-3 bg-primary-brown text-white font-bold rounded-lg transition hover:bg-dark-brown border-none cursor-pointer">
        <i class="fa-solid fa-rotate-right mr-2"></i> Muat Ulang Halaman
    </button>
@endsection
