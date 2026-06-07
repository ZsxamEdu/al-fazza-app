<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AL-Fazza Bakery</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/logo.png') }}">
    {{-- <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}"> --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <header class="sticky top-0 z-1200">
        <nav class="flex justify-between items-center py-1.5 px-[5%] bg-primary-brown text-white">
            <div class="flex items-center gap-5">
                <div class="h-12 md:h-20"><img loading="lazy" src="{{ asset('assets/img/footer-logo.png') }}" class="h-full" alt=""></div>
                <h2 class="m-0 text-base md:text-2xl">AL - Fazza Bakery</h2>
            </div>
            
            <div class="fixed top-0 -right-full w-64 h-screen bg-dark-brown flex-col items-start pt-20 px-8 lg:px-0 pb-10 z-[1050] transition-all duration-300 ease-in-out shadow-[-5px_0_20px_rgba(0,0,0,0.2)] lg:static lg:w-auto lg:h-auto lg:bg-transparent lg:flex-row lg:items-center lg:p-0 lg:shadow-none flex [&.open]:right-0" id="main-nav">
                <ul class="list-none flex flex-col lg:flex-row w-full lg:w-auto gap-0 lg:gap-12">
                    <li class="w-full lg:w-auto border-b border-white/10 lg:border-none"><a href="{{ url('/') }}" class="text-white block py-3.5 lg:py-0 text-lg md:text-lg no-underline font-semibold active">Beranda</a></li>
                    <li class="w-full lg:w-auto border-b border-white/10 lg:border-none relative inline-block group dropdown" id="categories-dropdown">
                        <a href="#" class="text-white block py-3.5 lg:py-0 text-lg md:text-lg no-underline font-semibold after:content-[''] after:absolute after:w-full after:h-5 after:-bottom-5 after:left-0">Kategori</a>
                        <ul class="dropdown-menu static lg:absolute top-full left-[-55%] text-left lg:text-center bg-white/10 lg:bg-white min-w-44 shadow-none lg:shadow-[0_8px_16px_rgba(0,0,0,0.1)] rounded-none lg:rounded-lg py-0 lg:py-2.5 z-[1000] list-none mt-0 lg:mt-4 max-h-0 lg:max-h-none overflow-hidden transition-[max-height] duration-300 ease block lg:hidden lg:group-hover:block [&.open]:max-h-[300px] lg:[&.open]:max-h-none">
                            <li><a href="{{ url('/kategori?jenis=bolu') }}" class="text-white/80 lg:text-text-dark py-2.5 px-5 block text-base font-normal transition-all duration-300 ease-in-out hover:bg-white/10 lg:hover:bg-primary-brown hover:text-white hover:pl-6">Aneka Bolu</a></li>
                            <li><a href="{{ url('/kategori?jenis=pastry') }}" class="text-white/80 lg:text-text-dark py-2.5 px-5 block text-base font-normal transition-all duration-300 ease-in-out hover:bg-white/10 lg:hover:bg-primary-brown hover:text-white hover:pl-6">Pastry</a></li>
                            <li><a href="{{ url('/kategori?jenis=cookies') }}" class="text-white/80 lg:text-text-dark py-2.5 px-5 block text-base font-normal transition-all duration-300 ease-in-out hover:bg-white/10 lg:hover:bg-primary-brown hover:text-white hover:pl-6">Cookies</a></li>
                            <li><a href="{{ url('/kategori?jenis=roti') }}" class="text-white/80 lg:text-text-dark py-2.5 px-5 block text-base font-normal transition-all duration-300 ease-in-out hover:bg-white/10 lg:hover:bg-primary-brown hover:text-white hover:pl-6">Roti</a></li>
                        </ul>
                    </li>
                    <li class="w-full lg:w-auto border-b border-white/10 lg:border-none"><a href="{{ url('/custom-order') }}" class="text-white block py-3.5 lg:py-0 text-lg lg:text-lg no-underline font-semibold">Custom Cake</a></li>
                    <li class="w-full lg:w-auto border-b border-white/10 lg:border-none"><a href="{{ url('/about') }}" class="text-white block py-3.5 lg:py-0 text-lg lg:text-lg no-underline font-semibold">Tentang Kami</a></li>
                </ul>
            </div>
            
            <div class="flex items-center gap-6 z-1100">
                <div class="text-2xl relative cursor-pointer" id="cart-btn">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="absolute -top-2.5 -right-2.5 bg-danger text-white text-xs py-0.5 px-1.5 rounded-full font-bold" id="cart-count">0</span>
                </div>
                <button class="hamburger lg:hidden flex flex-col gap-1.5 cursor-pointer bg-transparent border-none [&.active>span:first-child]:rotate-45 [&.active>span:first-child]:translate-y-[9px] [&.active>span:nth-child(2)]:opacity-0 [&.active>span:last-child]:-rotate-45 [&.active>span:last-child]:-translate-y-[9px]" id="hamburger-btn" aria-label="Toggle menu">
                    <span class="block w-6 h-[3px] bg-white transition-all duration-300 origin-center"></span>
                    <span class="block w-6 h-[3px] bg-white transition-all duration-300 origin-center"></span>
                    <span class="block w-6 h-[3px] bg-white transition-all duration-300 origin-center"></span>
                </button>
            </div>
        </nav>
        <div class="fixed inset-0 bg-black/50 z-[1040] opacity-0 invisible transition-all duration-300 ease [&.active]:opacity-100 [&.active]:visible" id="nav-overlay"></div>

        <div class="fixed top-0 -right-full w-full sm:w-96 h-screen bg-white shadow-[-5px_0_15px_rgba(0,0,0,0.1)] z-[1200] transition-[right] duration-400 ease-in-out flex flex-col [&.active]:right-0" id="cart-sidebar">
            <div class="flex justify-between items-center p-5 border-b border-border-light">
                <h3 class="m-0 text-lg font-bold">Keranjang</h3>
                <button id="close-cart" class="bg-transparent border-none text-2xl cursor-pointer text-text-dark"><i class="fas fa-times"></i></button>
            </div>
            
            <div id="cart-items" class="grow p-5 overflow-y-auto">
            </div>

            <div class="p-5 border-t border-border-light bg-white">
                <div class="flex justify-between mb-4 font-bold text-lg">
                    <span>Total :</span>
                    <span id="cart-total">Rp 0</span> 
                </div>
                <button class="btn-checkout w-full p-4 bg-primary-brown text-white border-none rounded cursor-pointer font-bold transition-colors duration-300 hover:bg-dark-brown">BELI SEKARANG</button>
            </div>
        </div>

        <div class="fixed top-0 left-0 w-full h-full bg-black/50 z-[1150] invisible opacity-0 transition-all duration-400 ease [&.active]:visible [&.active]:opacity-100" id="cart-overlay"></div>
    </header>

    <main>
        @yield('content') 
    </main>
    
    <footer class="bg-dark-brown text-white py-16 mt-12">
        <div class="flex flex-col md:flex-row justify-center items-center gap-8 lg:gap-[15%] text-center md:text-left px-5 lg:px-0">
            <div class="flex flex-col items-center">
                <img id="easter-logo" loading="lazy" src="{{ asset('assets/img/footer-logo.png') }}" alt="AL-Fazza Bakery Logo">
            </div>
            <div class="w-full max-w-sm md:max-w-none md:w-[400px]">
                <h4 class="mb-5 font-bold">Contact</h4>
                <div class="flex justify-center md:justify-start">
                    <div class="pr-5 flex flex-col items-center md:items-start">
                        <p class="text-base"><i class="fa-brands fa-whatsapp"></i></p>
                        <p class="text-base"><i class="fa-solid fa-envelope"></i></p>
                        <p class="text-base"><i class="fa-solid fa-map-marker-alt"></i></p>
                    </div>
                    <div class="flex flex-col text-left">
                        <p class="text-base">+62 812 2131 5946</p>
                        <p class="text-base">info@alfazzabakery.com</p>
                        <p class="text-base">Jl. Edelweis III No.16 blok J2</p>
                    </div>
                </div>
                <div class="mt-5 rounded-lg shadow-[0_4px_8px_rgba(0,0,0,0.1)] overflow-hidden">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d585.0202218415826!2d107.72891651234013!3d-6.948490799376005!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68c3cb151da7c1%3A0xa36f4d617456d7cc!2sAL-FAZZA!5e0!3m2!1sen!2sus!4v1775194929280!5m2!1sen!2sus" 
                        width="100%" 
                        height="200" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
                <div class="text-2xl mt-5 flex gap-4 justify-center md:justify-start">
                    <span><i class="fa-brands fa-instagram"></i></span> 
                    <span><i class="fa-brands fa-x-twitter"></i></span> 
                    <span><i class="fa-brands fa-facebook"></i></span> 
                    <span><i class="fa-brands fa-youtube"></i></span>
                </div>
            </div>
        </div>
    </footer>
    
    <script src="{{ asset('assets/js/script.js') }}?v={{ time() }}"></script>
    <script>
        let clickCount = 0;
        document.getElementById('easter-logo').addEventListener('click', function() {
            clickCount++;
            if (clickCount >= 5) {
                window.location.href = '/login';
            }
        });
    </script>
</body>
</html>