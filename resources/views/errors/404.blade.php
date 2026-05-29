@extends('layouts.error')
@section('title', '404 Tidak Ditemukan')
@section('content')
    <i class="fa-solid fa-cookie-bite text-7xl text-primary-brown mb-6 animate-bounce"></i>
    <h1 class="text-6xl font-black text-dark-brown mb-2">404</h1>
    <h2 class="text-2xl font-bold text-text-dark mb-4">Halaman Tidak Ditemukan</h2>
    <p class="text-text-medium mb-8">Oops! Sepertinya kue yang Anda cari tidak ada di toko kami, atau halamannya sudah dipindahkan.</p>
    <a href="{{ url('/') }}" class="inline-block px-8 py-3 bg-primary-brown text-white font-bold rounded-lg transition hover:bg-dark-brown no-underline">
        <i class="fa-solid fa-house mr-2"></i> Kembali ke Beranda
    </a>
@endsection
