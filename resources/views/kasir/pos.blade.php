<!DOCTYPE html>
<html lang="id">
<head>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mesin Kasir - Al-Fazza Bakery</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/logo.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
</head>
<body class="bg-bg-gray flex flex-col h-screen overflow-hidden">

    <header class="bg-dark-brown text-white py-4 px-5 flex justify-between items-center shadow-[0_2px_10px_rgba(0,0,0,0.1)] z-10">
        <h2 class="m-0"><i class="fa-solid fa-cash-register"></i> POS Al-Fazza</h2>
        <div class="flex items-center gap-4">
            <span><i class="fa-solid fa-user-tie"></i> Kasir: <strong>{{ Auth::user()->name ?? 'Kasir Tamu' }}</strong></span>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="bg-danger text-white border-none py-2 px-4 rounded-[5px] cursor-pointer font-bold transition duration-300 hover:bg-text-danger-dark"><i class="fa-solid fa-power-off"></i> Keluar</button>
            </form>
        </div>
    </header>

    <div class="flex flex-1 overflow-hidden">
        <div class="flex-2 p-5 overflow-y-auto bg-bg-cream">
            <input type="text" id="pos-search-input" class="w-full py-3 px-5 border border-border-medium rounded-lg text-base mb-5 outline-none transition duration-300 focus:border-primary-brown focus:ring-2 focus:ring-primary-brown/20" placeholder="Cari nama roti (Contoh: Cheese Cake)...">
            
            <div class="grid grid-cols-[repeat(auto-fill,minmax(180px,1fr))] gap-4">
                @foreach($products as $p)
                <div class="bg-white rounded-lg overflow-hidden shadow-[0_2px_5px_rgba(0,0,0,0.05)] cursor-pointer border-2 border-transparent transition duration-200 hover:border-primary-brown hover:-translate-y-0.5" onclick="addToPosCart('{{ $p->id }}', '{{ $p->nama }}', '{{ $p->harga }}')">
                    <img src="{{ asset($p->gambar) }}" alt="{{ $p->nama }}" class="w-full h-30 object-cover">
                    <div class="p-3 text-center">
                        <div class="font-bold text-[0.95rem] text-text-dark mb-1.5 whitespace-nowrap overflow-hidden text-ellipsis">{{ $p->nama }}</div>
                        <div class="text-primary-brown font-bold">Rp {{ number_format($p->harga, 0, ',', '.') }}</div>
                        <div class="text-[0.8rem] text-text-light mt-1">Sisa: {{ $p->stok }} pcs</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="flex-1 bg-white border-l border-border-light flex flex-col shadow-[-2px_0_10px_rgba(0,0,0,0.05)] z-5">
            <div class="p-5 bg-bg-light border-b border-border-light text-center">
                <h3>Pesanan Saat Ini</h3>
                <p class="text-text-light text-sm">Kasir: {{ Auth::user()->name ?? 'Kasir Tamu' }}</p>
            </div>
            
            <div class="flex-1 overflow-y-auto p-5" id="pos-cart-items">
                </div>

            <div class="p-5 bg-bg-light border-t border-border-light">
                <div class="flex justify-between mb-4 text-lg font-bold text-text-dark">
                    <span>Total Tagihan:</span>
                    <span id="pos-grand-total" class="text-danger">Rp 0</span>
                </div>
                
                <form action="{{ route('kasir.proses') }}" method="POST" id="form-pos">
                    @csrf
                    <input type="hidden" name="cart_data" id="cart-data-input">
                    <input type="hidden" name="payment_method" id="input-method">
                    <input type="hidden" name="amount_paid" id="input-paid">
                    <input type="hidden" name="change_amount" id="input-change">

                    
                    <button type="button" class="w-full bg-text-success text-white border-none p-4 rounded-lg text-lg font-bold cursor-pointer transition duration-300 hover:bg-success" onclick="openModal()"><i class="fa-solid fa-money-bill-wave"></i> PROSES PEMBAYARAN</button>
                </form>
            </div>

        </div>

    </div>
       <div id="payment-modal" class="hidden fixed inset-0 bg-black/60 z-2000 justify-center items-center">
        <div class="bg-white p-6 rounded-[10px] w-full max-w-100 shadow-[0_10px_30px_rgba(0,0,0,0.2)]">
            <h3 class="mb-4 border-b-2 border-border-light pb-2.5">Detail Pembayaran</h3>
            <div class="text-lg mb-5 flex justify-between">
                <span>Total Tagihan:</span>
                <strong id="modal-total-text" class="text-danger">Rp 0</strong>
            </div>

            <div class="mb-4">
                <label class="block font-bold mb-1">Metode Pembayaran</label>
                <select id="modal-method" class="w-full py-3 px-5 border border-border-medium rounded-lg text-base outline-none transition duration-300 focus:border-primary-brown focus:ring-2 focus:ring-primary-brown/20 mb-0" onchange="toggleCashInput()">
                    <option value="Cash">Tunai (Cash)</option>
                    <option value="QRIS">QRIS</option>
                    <option value="Transfer">Transfer Bank</option>
                </select>
            </div>

            <div id="cash-input-group" class="mb-4">
                <label class="block font-bold mb-1">Uang Diterima (Rp)</label>
                <input type="number" id="modal-paid" class="w-full py-3 px-5 border border-border-medium rounded-lg text-base outline-none transition duration-300 focus:border-primary-brown focus:ring-2 focus:ring-primary-brown/20 mb-0" placeholder="Contoh: 50000" onkeyup="calculateChange()" min="0" oninput="this.value = Math.abs(this.value)">
            </div>

            <div id="non-cash-info" class="hidden text-center mb-4 bg-bg-cream p-4 rounded-lg border border-dashed border-primary-brown">
                <p id="payment-instruction" class="text-sm mb-2.5"></p>
                
                <div id="qris-image" class="hidden">
                    <i class="fa-solid fa-qrcode" class="text-7xl text-text-dark"></i>
                    <p class="text-xs mt-1">Scan dengan M-Banking / Gopay / OVO</p>
                </div>
                
                <div id="transfer-info" class="hidden font-bold text-dark-brown text-lg">
                    BCA: 1234 5678 90<br>a.n Al-Fazza Bakery
                </div>
                
                <p class="text-xs text-danger mt-4 font-bold">
                    <i class="fa-solid fa-triangle-exclamation"></i> Kasir wajib cek mutasi masuk sebelum klik Simpan!
                </p>
            </div>

            <div class="mb-5">
                <label class="block font-bold mb-1">Kembalian</label>
                <input type="text" id="modal-change" class="w-full py-3 px-5 border border-border-medium rounded-lg text-base outline-none transition duration-300 focus:border-primary-brown focus:ring-2 focus:ring-primary-brown/20 mb-0 bg-border-light font-bold" value="Rp 0" readonly>
            </div>

            <div class="flex justify-end gap-2.5">
                <button type="button" class="bg-gray-400 text-white border-none py-2 px-6 rounded cursor-pointer font-bold transition duration-300 hover:bg-gray-500" onclick="closeModal()">Batal</button>
                <button type="button" class="w-auto py-2.5 px-5 bg-text-success text-white border-none rounded-lg font-bold cursor-pointer transition duration-300 hover:bg-success" onclick="submitFinalPayment()">Bayar & Simpan</button>
            </div>
        </div>
    </div>
    <script src="{{ asset('assets/js/script.js') }}"></script>
</body>
</html>
