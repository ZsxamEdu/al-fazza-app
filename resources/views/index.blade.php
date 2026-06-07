@extends('layouts.main')

@section('content')
    <!-- HERO SECTION DENGAN MASKOT -->
    <section class="bg-primary-brown pt-12 pb-16 px-5 md:px-12 lg:px-24 rounded-b-[30px] md:rounded-b-[50px] relative overflow-hidden mb-16 shadow-lg border-b-4 border-dark-brown/20">
        <!-- Dekorasi Background -->
        <!-- <div class="absolute -top-24 -right-24 w-72 h-72 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-[-10%] left-[20%] w-48 h-48 bg-dark-brown/15 rounded-full blur-2xl pointer-events-none"></div> -->
        
        <div class="flex flex-col lg:flex-row items-center justify-between max-w-6xl mx-auto gap-8 lg:gap-4 relative z-10">
            <!-- Left Side: Mascot -->
            <div class="w-full lg:w-1/2 flex justify-center lg:justify-start">
                <!-- User harus memastikan assets/img/mascot.png ada -->
                <img loading="lazy" src="{{ asset('assets/img/mascot.png') }}" alt="Chef Mascot" class="w-[70%] sm:w-[60%] lg:w-[85%] max-w-sm drop-shadow-[0_15px_25px_rgba(0,0,0,0.3)] hover:scale-105 transition-transform duration-500 ease-out">
            </div>
            
            <!-- Right Side: Text & CTA -->
            <div class="w-full lg:w-1/2 text-center lg:text-right text-white">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-black mb-5 leading-tight tracking-tight drop-shadow-md">Hallo!<br>Ayo Mulai</h1>
                <p class="text-sm md:text-lg lg:text-xl font-medium text-white/95 mb-8 max-w-lg mx-auto lg:mx-0 lg:ml-auto leading-relaxed drop-shadow-sm">
                    Jelajahi kelezatan aneka roti, kue, dan pastry autentik buatan tangan yang disajikan segar setiap hari.
                </p>
                <div class="flex flex-col sm:flex-row justify-center lg:justify-end gap-3 md:gap-4">
                    <button class="bg-white text-primary-brown py-3.5 px-8 rounded-xl font-extrabold text-base md:text-lg shadow-[0_5px_15px_rgba(0,0,0,0.15)] hover:bg-bg-cream hover:-translate-y-1 transition-all duration-300" onclick="window.location.href='#terlaris'">Lihat Produk</button>
                </div>
            </div>
        </div>
    </section>

    <!-- TERLARIS SECTION (GRID BARU) -->
    <section id="terlaris" class="max-w-6xl mx-auto px-5 lg:px-0 mb-16">
        <div class="text-center mb-10">
            <h2 class="text-3xl md:text-4xl font-extrabold text-black inline-flex items-center gap-3">
                <i class="fas fa-crown text-yellow-500 text-2xl md:text-3xl"></i> Paling Laris
            </h2>
            <p class="text-text-medium mt-3 text-sm md:text-base">Produk favorit pilihan pelanggan Al-Fazza Bakery.</p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
            @foreach($terlaris as $item)
            <div class="bg-white border border-border-cream rounded-2xl shadow-[0_4px_15px_rgba(0,0,0,0.05)] overflow-hidden cursor-pointer transition-all duration-300 hover:shadow-[0_8px_25px_rgba(0,0,0,0.1)] hover:-translate-y-1.5 flex flex-col h-full group" onclick="window.location.href='{{ url('/detail/') }}/{{ $item->id }}'">
                <!-- Image Container with Badge -->
                <div class="relative w-full aspect-square bg-[#FBF8F5] overflow-hidden p-5 flex items-center justify-center border-b border-border-cream/50">
                    <img loading="lazy" src="{{ asset($item->gambar) }}" alt="{{ $item->nama }}" class="w-full h-full object-contain drop-shadow-md group-hover:scale-110 transition-transform duration-500">
                    
                    <!-- Badge Terjual -->
                    <div class="absolute top-3 left-3 bg-danger text-white text-xs md:text-sm font-bold py-1 px-2.5 rounded-full shadow-md flex items-center gap-1.5 z-10">
                        <i class="fas fa-fire"></i> {{ $item->total_terjual ?? 0 }} Terjual
                    </div>
                </div>
                
                <!-- Card Content -->
                <div class="p-4 md:p-5 flex-1 flex flex-col">
                    <h3 class="text-base md:text-lg font-extrabold text-text-darker mb-1 truncate">{{ $item->nama }}</h3>
                    <span class="text-xs md:text-sm text-text-light mb-4 block font-medium">{{ ucfirst($item->kategori) }}</span>
                    <div class="mt-auto flex justify-between items-center">
                        <span class="font-black text-primary-brown text-sm md:text-lg">Rp {{ number_format($item->harga, 0, ',', '.') }}</span>
                        <div class="w-8 h-8 md:w-10 md:h-10 bg-primary-brown rounded-full flex items-center justify-center text-white transition-colors shadow-sm group-hover:bg-dark-brown group-hover:scale-110">
                            <i class="fas fa-chevron-right text-xs md:text-sm"></i>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </section>

    <section class="new-bakery mt-5">
        <h2 class="text-center my-10 font-extrabold text-2xl md:text-3xl flex justify-center items-center gap-3 text-black">
            <span class="bg-[#FF4A8D] text-white text-xs md:text-sm px-2.5 py-1 rounded-md font-black tracking-wide">NEW</span> Bakery Terbaru
        </h2>
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

    <h2 class="text-center my-10 font-extrabold text-2xl md:text-3xl text-black">Wujudkan Kue Impianmu!</h2>
    <section class="bg-cover bg-center lg:mx-[13%] mx-[5%] mb-16 rounded-xl text-center py-12 md:py-16 px-6 md:px-10 text-white shadow-[0_10px_30px_rgba(0,0,0,0.2)] relative overflow-hidden" style="background-image: linear-gradient(rgba(30, 20, 15, 0.75), rgba(30, 20, 15, 0.75)), url('{{ asset('assets/img/hero-bg.png') }}');">
        <!-- Inner Border for custom order banner -->
        <div class="absolute inset-2 border-2 border-[#D8C4A9]/40 rounded-lg pointer-events-none"></div>
        <div class="custom-cake-content relative z-10">
            <h3 class="text-xl md:text-3xl mb-4 font-extrabold tracking-wide flex justify-center items-center gap-3">
                Rancang Kue Sendiri
            </h3>
            <p class="text-xs md:text-base leading-[1.7] mb-6 max-w-3xl mx-auto text-[#E8DED3]">Pesan kue ulang tahun, anniversary, atau perayaan spesial lainnya dengan desain, rasa, dan ukuran yang sepenuhnya bisa disesuaikan dengan keinginanmu. Jadikan momen spesialmu lebih berkesan bersama Al-Fazza Bakery.</p>
            <button class="bg-[#BFA186] text-white border-none py-2.5 px-6 md:py-3 md:px-8 rounded shadow-lg text-sm md:text-base font-bold cursor-pointer mt-2 transition-colors duration-300 ease-in hover:bg-[#A58B76]" onclick="window.location.href='{{ url('/custom-order') }}'">Pesan Sekarang</button>
        </div>
    </section>

    <h2 class="text-center my-10 font-extrabold text-2xl md:text-3xl text-black">Tentang Kami</h2>
    <section class="flex flex-wrap lg:flex-nowrap items-center lg:mx-[13%] mx-[5%] mb-12 bg-[#FFF5F5] rounded-3xl shadow-[0_8px_20px_rgba(0,0,0,0.05)] overflow-hidden border border-[#F0E6DD]">
        <div class="lg:w-1/2 w-full h-full">
            <img loading="lazy" src="{{ asset('assets/img/tempat.png') }}" alt="Dapur Al-Fazza Bakery" class="w-full h-full lg:min-h-132.5 min-h-64 object-cover block"> 
        </div>
        <div class="lg:w-1/2 w-full py-10 px-8 md:px-12 text-center lg:text-left flex flex-col items-center lg:items-start">
            <h3 class="text-2xl md:text-3xl text-black mb-5 font-extrabold">Kisah di Balik Al-Fazza</h3>
            <p class="text-sm md:text-base text-text-medium leading-[1.8] mb-6 text-center lg:text-justify max-w-md">Berawal dari dapur keluarga, Al-Fazza Bakery hadir untuk menyajikan aneka roti dan kue dengan cita rasa autentik. Setiap produk kami dibuat dengan penuh cinta dan dipanggang langsung oleh tangan terampil sang Ayah yang berdedikasi tinggi dalam menjaga kualitas bahan dan rasa.</p>
            <button class="bg-[#BFA186] text-white border-none py-3 px-8 rounded shadow-sm text-sm md:text-base font-bold cursor-pointer transition-colors duration-300 ease-in hover:bg-[#A58B76]" onclick="window.location.href='{{ url('/about') }}'">Jelajahi Kami</button>
        </div>
    </section>
    <script src="{{ asset('assets/js/script.js') }}?v={{ time() }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            if (!sessionStorage.getItem('welcome_shown')) {
                Swal.fire({
                    icon: 'info',
                    iconHtml: `<img src="{{ asset('assets/img/footer-logo.png') }}" style="width: 50px; height: 50px; object-fit: contain;">`,
                    title: 'Selamat Datang di Al-Fazza',
                    html: '<span>Yuk, Tekan lanjutkan untuk melihat</span><br><span>etalase produk kami</span>',
                    showConfirmButton: true,
                    confirmButtonText: 'Lanjutkan',
                    allowOutsideClick: false
                });
                sessionStorage.setItem('welcome_shown', 'true');
            }
        });
    </script>
@endsection