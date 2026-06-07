@extends('layouts.error')
@section('title', '403 Akses Ditolak')
@section('content')
    <i class="fa-solid fa-lock text-7xl text-danger mb-6"></i>
    <h1 class="text-6xl font-black text-dark-brown mb-2">403</h1>
    <h2 class="text-2xl font-bold text-text-dark mb-4">Akses Ditolak</h2>
    <p class="text-text-medium mb-8">Maaf, Anda tidak memiliki izin untuk masuk ke ruangan ini. Area ini dikhususkan untuk jabatan tertentu.</p>
    <a href="{{ url()->previous() !== url()->current() ? url()->previous() : url('/') }}" class="inline-block px-8 py-3 bg-primary-brown text-white font-bold rounded-lg transition hover:bg-dark-brown no-underline">
        <i class="fa-solid fa-arrow-left mr-2"></i> Kembali
    </a>
@endsection
