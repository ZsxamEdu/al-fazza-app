@extends('layouts.main')

@section('content')
    <main class="kategori-container">
        <div class="banner-wrapper">
            <div class="promo-banner dark-banner">
                <h2>Platform Terbaik untuk order Pastry & Bakery</h2>
                <p>Nikmati Pastry & Bakery sesuai selera lidah dan dompetmu!</p>
                <!-- <button class="btn-brown">Beli Sekarang</button> -->
            </div>
            <div class="promo-banner dark-banner">
                <h2>Pastry unik Bakery menarik, seleramu pasti melirik!</h2>
                <p>Ayo dapatkan aneka cemilan keseharianmu dari sekarang!</p>
                <!-- <button class="btn-brown">Beli Sekarang</button> -->
            </div>
        </div>

        
        <div class="kategori-grid">
            @foreach($products as $p)
                <div class="card">
                    <div class="card-header">
                        <div class="title-cat">
                            <h3>{{ $p->nama }}</h3>
                            <span>{{ $p->tipe }}</span>
                        </div>
                    </div>
                    <div class="card-img-wrapper">
                        <div class="rating"><i class="fa-solid fa-star"></i>{{ $p->rating }}</div>
                        <img src="{{ asset($p->gambar) }}" alt="{{ $p->nama }}">
                    </div>
                    <div class="card-footer">
                        <p>Rp {{ number_format($p->harga, 0, ',', '.') }}</p>
                        <div>
                            <button class="btn-add-cart" onclick="addToCart('{{ $p->id }}', '{{ $p->nama }}', {{ $p->harga }}, '{{ asset($p->gambar) }}')">Beli</button>
                            <a href="{{ url('/detail/' . $p->id) }}" class="btn-brown">Detail</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="kategori-header">
            <h3>{{ $judul }}</h3>
        </div>
    </main>
    
    <script>
        document.addEventListener("DOMContentLoaded", renderKategoriProduk);
    </script>
    @endsection
