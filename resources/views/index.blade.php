@extends('layouts.main')

@section('content')
    <section class="flex flex-col lg:flex-row-reverse items-center justify-center py-12 px-5 md:px-[15%] gap-8 md:gap-12">
        <div class="w-[80%] lg:min-w-[40%]">
            <img loading="lazy" src="{{ asset('assets/img/pisangbolen 1.png') }}" alt="Pisang Bolen" class="w-full h-auto">
        </div>
        <div class="max-w-3xl text-center lg:text-left">
            <h1 class="text-4xl lg:text-5xl mb-5 font-bold text-black">Pisang Bolen</h1>
            <p class="font-medium">Perpaduan pisang dan cokelat lumer atau keju gurih yang dibalut kulit pastry berlapis yang renyah di luar namun lembut di dalam.</p>
            <button class="bg-primary-brown text-white border-none text-base py-2.5 px-6 rounded cursor-pointer mt-5 font-bold" onclick="window.location.href='{{ url('/detail/25') }}'">Beli Sekarang</button>
        </div>
    </section>

    <section class="bg-primary-brown text-center p-8 text-white">
        <h2 class="text-3xl font-bold mb-6 mt-0">Terlaris</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 justify-center gap-4 md:gap-6 mx-auto max-w-5xl">
            @foreach($terlaris as $item)
            <div class="bg-white rounded-xl p-2.5 cursor-pointer transition-transform duration-300 ease-in hover:scale-105 flex items-center justify-center aspect-square" onclick="window.location.href='{{ url('/detail/') }}/{{ $item->id }}'">
                <img loading="lazy" src="{{ asset($item->gambar) }}" alt="{{ $item->nama }}" class="w-full h-full object-contain rounded-lg">
            </div>
            @endforeach
        </div>
    </section>

    <section class="new-bakery">
        <h2 class="text-center my-10 font-bold text-3xl">Bakery Terbaru</h2>
        <div class="bg-primary-brown grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-3 md:gap-5 p-3 md:p-5 lg:mx-[13%] mx-[5%] my-5 rounded">
            @foreach($products as $p)
                <div class="bg-white rounded-xl shadow-sm flex flex-col h-full">
                    <div class="flex justify-between pt-3 md:pt-5 px-3 md:px-8 pb-0">
                        <div class="title-cat w-full">
                            <h3 class="text-sm md:text-xl text-text-darker mb-1 font-extrabold truncate">{{ $p->nama }}</h3>
                            <span class="text-xs md:text-base text-text-slate font-semibold">{{ ucfirst($p->kategori) }}</span>
                        </div>
                    </div>
                    <div class="relative w-full mb-2 md:mb-4 flex-1">
                        <div class="absolute top-1 left-3 md:top-2.5 md:left-10 bg-white/95 py-0.5 px-1.5 md:py-1.5 md:px-2.5 rounded text-xs md:text-sm font-extrabold text-text-dark flex items-center gap-1 shadow-[0_2px_5px_rgba(0,0,0,0.1)] z-10"><i class="fa-solid fa-star text-star"></i>{{ number_format($p->rating, 1) }}</div>
                        <img loading="lazy" src="{{ asset($p->gambar) }}" alt="{{ $p->nama }}" class="w-full rounded px-2 md:px-8 object-cover block">
                    </div>
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center px-3 md:px-8 pb-3 md:pb-5 mt-auto gap-2">
                        <p class="text-sm md:text-xl font-bold">Rp {{ number_format($p->harga, 0, ',', '.') }}</p>
                        <div class="flex flex-row gap-1.5 md:gap-2.5 w-full md:w-auto">
                            <button class="flex-1 md:flex-none bg-secondary text-white border-none py-1.5 md:py-2.5 px-0 md:px-5 rounded font-bold cursor-pointer hover:bg-dark-brown text-xs md:text-base" onclick="addToCart('{{ $p->id }}', '{{ $p->nama }}', {{ $p->harga }}, '{{ asset($p->gambar) }}')">Beli</button>
                            <a href="{{ url('/detail/' . $p->id) }}" class="flex-1 md:flex-none bg-primary-brown text-white border-none py-1.5 md:py-2.5 px-0 md:px-5 rounded font-bold cursor-pointer no-underline block text-center hover:bg-dark-brown text-xs md:text-base">Detail</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <h2 class="text-center my-10 font-bold text-3xl">Tentang Kami</h2>
    <section class="flex flex-wrap lg:flex-nowrap items-center lg:mx-[13%] mx-[5%] mb-10 bg-white rounded-xl shadow-[0_8px_20px_rgba(166,124,82,0.1)] overflow-hidden border border-border-cream">
        <div class="lg:w-1/2 w-full h-full">
            <img loading="lazy" src="{{ asset('assets/img/tempat.png') }}" alt="Dapur Al-Fazza Bakery" class="w-full h-full lg:min-h-132.5 min-h-75 object-cover block"> 
        </div>
        <div class="lg:w-1/2 w-full py-10 px-12">
            <h3 class="text-2xl md:text-3xl text-dark-brown mb-4 font-bold">Kisah di Balik Al-Fazza</h3>
            <p class="text-sm md:text-base text-text-medium leading-[1.7] mb-4 text-justify">Berawal dari dapur keluarga, Al-Fazza Bakery hadir untuk menyajikan aneka roti dan kue dengan cita rasa autentik. Setiap produk kami dibuat dengan penuh cinta dan dipanggang langsung oleh tangan terampil sang Ayah yang berdedikasi tinggi dalam menjaga kualitas bahan dan rasa.</p>
            <p class="text-sm md:text-base text-text-medium leading-[1.7] mb-4 text-justify">Sementara itu, sang Ibu dengan hangat akan menyapa dan memastikan setiap pesanan sampai ke tangan Anda dengan pelayanan terbaik. Kami percaya, setiap gigitan dari kue Al-Fazza membawa kehangatan rumah untuk Anda nikmati bersama orang-orang tersayang.</p>
            <button class="bg-primary-brown text-white border-none py-3 px-7 rounded text-base font-bold cursor-pointer mt-4 transition-colors duration-300 ease-in hover:bg-dark-brown" onclick="window.location.href='{{ url('/about') }}'">Jelajahi Kami</button>
        </div>
    </section>

    <h2 class="text-center my-10 font-bold text-3xl">Wujudkan Kue Impianmu!</h2>
    <section class="bg-cover bg-center lg:mx-[13%] mx-[5%] mb-16 rounded-xl text-center py-16 px-10 text-white shadow-[0_8px_20px_rgba(0,0,0,0.15)]" style="background-image: linear-gradient(rgba(74, 59, 50, 0.8), rgba(74, 59, 50, 0.8)), url('{{ asset('assets/img/hero-bg.png') }}');">
        <div class="custom-cake-content">
            <h3 class="text-2xl md:text-4xl mb-4 font-extrabold">Rancang Kue Sendiri</h3>
            <p class="text-sm md:text-lg leading-[1.6] mb-6 max-w-3xl mx-auto text-border-cream">Pesan kue ulang tahun, anniversary, atau perayaan spesial lainnya dengan desain, rasa, dan ukuran yang sepenuhnya bisa disesuaikan dengan keinginanmu. Jadikan momen spesialmu lebih berkesan bersama Al-Fazza Bakery.</p>
            <button class="bg-primary-brown text-white border-none py-3 px-7 rounded text-base font-bold cursor-pointer mt-4 transition-colors duration-300 ease-in hover:bg-dark-brown" onclick="window.location.href='{{ url('/custom-order') }}'">Pesan Sekarang</button>
        </div>
    </section>
    <script src="{{ asset('assets/js/script.js') }}?v={{ time() }}"></script>
@endsection