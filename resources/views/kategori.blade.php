@extends('layouts.main')

@section('content')
    <main class="max-w-6xl my-8 mx-auto px-5">
        <div class="flex flex-col md:flex-row gap-5 mb-10">
            <div class="flex-1 bg-bg-banner bg-cover bg-center bg-no-repeat text-white p-6 md:p-10 rounded-xl relative overflow-hidden" style="background-image: url('{{ asset('assets/img/bg.png') }}');">
                <h2 class="text-xl md:text-3xl mb-2.5">Platform Terbaik untuk order Pastry & Bakery</h2>
                <p class="text-sm text-white/80 md:mb-5">Nikmati Pastry & Bakery sesuai selera lidah dan dompetmu!</p>
            </div>
            <div class="flex-1 bg-bg-banner bg-cover bg-center bg-no-repeat text-white p-6 md:p-10 rounded-xl relative overflow-hidden" style="background-image: url('{{ asset('assets/img/bg.png') }}');">
                <h2 class="text-xl md:text-3xl mb-2.5">Pastry unik Bakery menarik, seleramu pasti melirik!</h2>
                <p class="text-sm text-white/80 md:mb-5 ">Ayo dapatkan aneka cemilan keseharianmu dari sekarang!</p>
            </div>
        </div>

        
        <div class="flex justify-center items-center mb-6 gap-3">
            <i class="fa-solid fa-cookie-bite text-3xl md:text-4xl" style="color: #fd4b82;"></i>
            <h3 class="text-black text-3xl md:text-4xl font-extrabold m-0">{{ $judul }}</h3>
        </div>
        <div class="bg-primary-brown p-5 md:p-8 rounded-xl mb-10">
            <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 md:gap-5">
                @foreach($products as $p)
                <div class="bg-white rounded-xl shadow-sm border border-border-cream flex flex-col h-full">
                    <div class="flex justify-between pt-3 md:pt-5 px-3 md:px-8 pb-0">
                        <div class="title-cat w-full">
                            <h3 class="text-sm md:text-xl text-text-darker mb-1 font-extrabold truncate">{{ $p->nama }}</h3>
                            <span class="text-xs md:text-base text-text-slate font-semibold">{{ $p->tipe }}</span>
                        </div>
                    </div>
                    <div class="relative w-full mb-2 md:mb-4 flex-1">
                        <div class="absolute top-1 left-3 md:top-2.5 md:left-10 bg-white/95 py-0.5 px-1.5 md:py-1.5 md:px-2.5 rounded text-xs md:text-sm font-extrabold text-text-dark flex items-center gap-1 shadow-[0_2px_5px_rgba(0,0,0,0.1)] z-10"><i class="fa-solid fa-star text-star"></i>{{ $p->rating }}</div>
                        <img loading="lazy" src="{{ asset($p->gambar) }}" alt="{{ $p->nama }}" class="w-full h-28 md:h-48 rounded px-2 md:px-8 object-cover block mt-2">
                    </div>
                    <div class="flex flex-col justify-between items-start px-3 md:px-8 pb-3 md:pb-5 mt-auto gap-2">
                        <p class="text-sm md:text-xl font-bold mb-0">Rp {{ number_format($p->harga, 0, ',', '.') }}</p>
                        <div class="flex flex-row gap-1.5 md:gap-2.5 w-full md:w-auto">
                            <button class="flex-1 md:flex-none bg-secondary text-white border-none py-1.5 md:py-2.5 px-0 md:px-5 rounded font-bold cursor-pointer hover:bg-dark-brown text-xs md:text-base" onclick="addToCart('{{ $p->id }}', '{{ $p->nama }}', {{ $p->harga }}, '{{ asset($p->gambar) }}')">Beli</button>
                            <a href="{{ url('/detail/' . $p->id) }}" class="flex-1 md:flex-none bg-primary-brown text-white border-none py-1.5 md:py-2.5 px-0 md:px-5 rounded font-bold cursor-pointer no-underline block text-center hover:bg-dark-brown text-xs md:text-base">Detail</a>
                        </div>
                    </div>
                </div>
            @endforeach
            </div>
        </div>
    </main>
    
    <script>
        document.addEventListener("DOMContentLoaded", renderKategoriProduk);
    </script>
    @endsection
