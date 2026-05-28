<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Al-Fazza Bakery</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/logo.png') }}">
    {{-- <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}"> --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
</head>
<body class="flex justify-center items-center min-h-screen bg-bg-cream m-0 font-sans">

    <div class="bg-white w-full max-w-md py-10 px-8 rounded-xl shadow-[0_8px_20px_rgba(166,124,82,0.15)] border border-border-cream text-center">
        <div class="mb-8">
            <i class="fa-solid fa-shop text-5xl text-primary-brown mb-4"></i>
            <h2 class="text-dark-brown text-3xl mb-1.5 font-extrabold">Selamat Datang</h2>
            <p class="text-text-slate text-base m-0">Silakan masuk ke sistem Al-Fazza</p>
        </div>

        @if ($errors->any())
            <div class="bg-danger-bg text-danger p-2.5 rounded mb-5 text-sm text-left">
                <i class="fa-solid fa-circle-exclamation"></i> {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="text-left mb-5">
                <label class="block mb-2 font-semibold text-dark-brown text-base"><i class="fa-solid fa-envelope"></i> Alamat Email</label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="Masukkan alamat email" required class="w-full py-3 px-4 border border-border-medium rounded-lg font-inherit text-sm transition-colors duration-300 outline-none focus:border-primary-brown focus:ring-[2px] focus:ring-[#a67c5233]">
            </div>
            
            <div class="text-left mb-5">
                <label class="block mb-2 font-semibold text-dark-brown text-base"><i class="fa-solid fa-lock"></i> Password</label>
                <input type="password" name="password" placeholder="Masukkan password" required class="w-full py-3 px-4 border border-border-medium rounded-lg font-inherit text-sm transition-colors duration-300 outline-none focus:border-primary-brown focus:ring-[2px] focus:ring-[#a67c5233]">
            </div>
            
            <button type="submit" class="w-full p-4 bg-primary-brown text-white border-none rounded-lg text-lg font-bold cursor-pointer transition-colors duration-300 mt-2.5 hover:bg-dark-brown">
                <i class="fa-solid fa-right-to-bracket mr-1.5"></i> Masuk
            </button>
        </form>
    </div>

</body>
</html>