<!DOCTYPE html>
<html lang="id">
<head>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi Berhasil - Al-Fazza</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/logo.png') }}">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    
    
</head>
<body>

    <div class="py-13 px-5 flex flex-col items-center bg-bg-cream min-h-screen">
        
        <div class="text-center mb-8">
            <i class="fa-solid fa-circle-check text-6xl text-success mb-4"></i>
            <h1>Transaksi Berhasil!</h1>
            <p>Invoice: <strong>{{ $transaksi->invoice_number }}</strong></p>
        </div>

        <div class="bg-white p-8 rounded-lg shadow-[0_4px_15px_rgba(0,0,0,0.1)] w-full max-w-[400px] font-mono border border-border-medium">
            <div class="text-center mb-5">
                <h2 class="m-0">AL-FAZZA BAKERY</h2>
                <p class="text-sm">Jl. Edelweis III No.16 blok J2</p>
                <div class="border-t border-dashed border-black my-[15px]"></div>
            </div>

            <div class="text-sm mb-4 ">
                <p><span class="inline-block w-20">No</span>: {{ $transaksi->invoice_number }}</p>
                <p><span class="inline-block w-20">Kasir</span>: {{ Auth::user()->name }}</p>
                <p><span class="inline-block w-20">Tanggal</span>: {{ $transaksi->created_at->format('d/m/Y H:i') }}</p>
                <div class="border-t border-dashed border-black my-[15px]"></div>
            </div>

            <div class="items-list">
                @foreach($transaksi->details as $detail)
                <div class="mb-2.5">
                    <div>{{ $detail->product->nama }}</div>
                    <div class="flex justify-between">
                        <span>{{ $detail->qty }} x {{ number_format($detail->price, 0, ',', '.') }}</span>
                        <span>{{ number_format($detail->subtotal, 0, ',', '.') }}</span>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="border-t border-dashed border-black my-[15px]"></div>

            <div class="flex justify-between font-bold text-[1.2rem] mt-[10px]">
                <span>TOTAL :</span>
                <span>Rp {{ number_format($transaksi->total_amount, 0, ',', '.') }}</span>
            </div>
            
            <div class="flex justify-between text-sm mt-1">
                <span>Metode Bayar :</span>
                <span>{{ $transaksi->payment_method }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span>Tunai :</span>
                <span>Rp {{ number_format($transaksi->amount_paid, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span>Kembali :</span>
                <span>Rp {{ number_format($transaksi->change_amount, 0, ',', '.') }}</span>
            </div>

            <div class="text-center mt-10 text-sm">
                <p>Terima Kasih Atas Kunjungan Anda</p>
                <p>~ Happy Eating ~</p>
            </div>
        </div>

        <div class="mt-[30px] flex gap-[15px]">
            <a href="{{ route('kasir.pos') }}" class="py-3 px-6 rounded-lg font-bold cursor-pointer no-underline inline-block border-none text-base bg-primary-brown text-white hover:bg-dark-brown transition">
                <i class="fa-solid fa-cart-plus"></i> Transaksi Baru
            </a>
            <a href="{{ route('kasir.cetak', $transaksi->id) }}" target="_blank" class="py-3 px-6 rounded-lg font-bold cursor-pointer no-underline inline-block border-none text-base bg-btn-navy text-white hover:bg-btn-navy-hover transition">
                <i class="fa-solid fa-print"></i> Cetak Struk
            </a>
        </div>

    </div>

</body>
</html>
