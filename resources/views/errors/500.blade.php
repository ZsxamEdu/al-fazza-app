@extends('layouts.error')
@section('title', '500 Kesalahan Sistem')
@section('content')
    <i class="fa-solid fa-fire-burner text-7xl text-[#e67e22] mb-6"></i>
    <h1 class="text-6xl font-black text-dark-brown mb-2">500</h1>
    <h2 class="text-2xl font-bold text-text-dark mb-4">Kesalahan Sistem</h2>
    <p class="text-text-medium mb-8">Waduh! Sepertinya oven kami sedang bermasalah. Teknisi kami sedang memperbaikinya, mohon kembali lagi nanti.</p>
    <a href="{{ url('/') }}" class="inline-block px-8 py-3 bg-primary-brown text-white font-bold rounded-lg transition hover:bg-dark-brown no-underline">
        <i class="fa-solid fa-house mr-2"></i> Kembali ke Beranda
    </a>
@endsection
