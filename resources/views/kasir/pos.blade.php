<!DOCTYPE html>
<html lang="id">
<head>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mesin Kasir - Al-Fazza Bakery</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/logo.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script type="text/javascript"
            src="{{ config('midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}"
            data-client-key="{{ config('midtrans.client_key') }}"></script>
</head>
<body class="bg-bg-gray flex flex-col h-screen overflow-hidden">

    <header class="bg-dark-brown text-white py-4 px-5 flex justify-between items-center shadow-[0_2px_10px_rgba(0,0,0,0.1)] z-10">
        <h2 class="m-0"><i class="fa-solid fa-cash-register"></i> POS Al-Fazza</h2>
        <!-- Desktop Actions -->
        <div class="hidden md:flex items-center gap-4">
            <span><i class="fa-solid fa-user-tie"></i> Kasir: <strong>{{ Auth::user()->name ?? 'Kasir Tamu' }}</strong></span>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="bg-danger text-white border-none py-2 px-4 rounded-[5px] cursor-pointer font-bold transition duration-300 hover:bg-text-danger-dark"><i class="fa-solid fa-power-off"></i> Keluar</button>
            </form>
        </div>
        <!-- Mobile Actions -->
        <button id="mobile-settings-btn" class="md:hidden text-white text-2xl focus:outline-none cursor-pointer">
            <i class="fa-solid fa-gear"></i>
        </button>
    </header>

    <div class="flex flex-1 overflow-hidden relative">
        <div class="flex-[2] p-5 overflow-y-auto bg-bg-cream w-full">
            <input type="text" id="pos-search-input" class="w-full py-3 px-5 border border-border-medium rounded-lg text-base mb-5 outline-none transition duration-300 focus:border-primary-brown focus:ring-2 focus:ring-primary-brown/20" placeholder="Cari nama roti (Contoh: Cheese Cake)...">
            
            <div class="grid grid-cols-[repeat(auto-fill,minmax(180px,1fr))] gap-4">
                @foreach($products as $p)
                <div class="pos-card bg-white rounded-lg overflow-hidden shadow-[0_2px_5px_rgba(0,0,0,0.05)] cursor-pointer border-2 border-transparent transition duration-200 hover:border-primary-brown hover:-translate-y-0.5" onclick="addToPosCart('{{ $p->id }}', '{{ $p->nama }}', '{{ $p->harga }}', {{ $p->stok }})">
                    <img loading="lazy" src="{{ asset($p->gambar) }}" alt="{{ $p->nama }}" class="w-full h-30 object-cover">
                    <div class="p-3 text-center">
                        <div class="pos-card-title font-bold text-[0.95rem] text-text-dark mb-1.5 whitespace-nowrap overflow-hidden text-ellipsis">{{ $p->nama }}</div>
                        <div class="text-primary-brown font-bold">Rp {{ number_format($p->harga, 0, ',', '.') }}</div>
                        <div class="text-[0.8rem] text-text-light mt-1" id="stok-display-{{ $p->id }}">Sisa: {{ $p->stok }} pcs</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Mobile Overlay -->
        <div id="pos-cart-overlay" class="fixed inset-0 bg-black/50 z-[1400] hidden md:hidden"></div>
        
        <div id="pos-cart-panel" class="fixed top-0 right-0 w-80 h-full transform translate-x-full md:relative md:translate-x-0 md:w-auto transition-transform z-[1500] bg-white border-l border-border-light flex flex-col shadow-[-2px_0_10px_rgba(0,0,0,0.05)] md:flex-1">
            <div class="p-5 bg-bg-light border-b border-border-light flex justify-between md:justify-center items-center text-center">
                <div>
                    <h3 class="m-0">Pesanan Saat Ini</h3>
                    <p class="text-text-light text-sm m-0">Kasir: {{ Auth::user()->name ?? 'Kasir Tamu' }}</p>
                </div>
                <button id="close-pos-cart" class="md:hidden bg-transparent border-none text-2xl cursor-pointer text-text-dark">
                    <i class="fa-solid fa-times"></i>
                </button>
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

    <!-- Floating Action Button for Mobile -->
    <button id="fab-cart" class="fixed bottom-5 right-5 w-14 h-14 bg-primary-brown rounded-full flex items-center justify-center text-white md:hidden shadow-lg z-[1300] cursor-pointer border-none hover:bg-dark-brown">
        <i class="fa-solid fa-shopping-cart text-xl"></i>
        <span id="fab-cart-count" class="absolute -top-1 -right-1 bg-danger text-white text-xs py-0.5 px-1.5 rounded-full font-bold">0</span>
    </button>
       <div id="payment-modal" class="hidden fixed inset-0 bg-black/60 z-[2000] justify-center items-center">
        <div class="bg-white p-6 rounded-[10px] w-[90%] max-w-lg shadow-[0_10px_30px_rgba(0,0,0,0.2)]">
            <h3 class="mb-4 border-b-2 border-border-light pb-2.5">Detail Pembayaran</h3>
            <div class="text-lg mb-5 flex justify-between">
                <span>Total Tagihan:</span>
                <strong id="modal-total-text" class="text-danger">Rp 0</strong>
            </div>

            <div class="mb-4">
                <label class="block font-bold mb-1">Metode Pembayaran</label>
                <select id="modal-method" class="w-full py-3 px-5 border border-border-medium rounded-lg text-base outline-none transition duration-300 focus:border-primary-brown focus:ring-2 focus:ring-primary-brown/20 mb-0" onchange="toggleCashInput()">
                    <option value="Cash">Tunai (Cash)</option>
                    <option value="Transfer">Transfer / QRIS (Midtrans)</option>
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
                <button type="button" id="btn-submit-payment" class="w-auto py-2.5 px-5 bg-text-success text-white border-none rounded-lg font-bold cursor-pointer transition duration-300 hover:bg-success" onclick="submitFinalPayment()">Bayar & Simpan</button>
            </div>
        </div>
    </div>

    <!-- Mobile Settings Overlay -->
    <div id="settings-overlay" class="fixed inset-0 bg-black/50 z-[1400] hidden md:hidden"></div>
    <div id="settings-panel" class="fixed top-0 right-0 w-64 h-full transform translate-x-full transition-transform z-[1500] bg-white flex flex-col shadow-[-2px_0_10px_rgba(0,0,0,0.05)] md:hidden">
        <div class="p-5 bg-dark-brown text-white border-b border-border-light flex justify-between items-center">
            
            <button id="close-settings" class="bg-transparent border-none text-2xl cursor-pointer text-white">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>
        <div class="p-5 flex flex-col gap-4">
            <div class="text-text-dark border-b border-border-light pb-4">
                <i class="fa-solid fa-user-tie text-primary-brown"></i> Kasir:<br>
                <strong class="text-lg">{{ Auth::user()->name ?? 'Kasir Tamu' }}</strong>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full bg-danger text-white border-none py-3 px-4 rounded-[5px] cursor-pointer font-bold transition duration-300 hover:bg-text-danger-dark text-lg"><i class="fa-solid fa-power-off"></i> Keluar</button>
            </form>
        </div>
    </div>
    <script src="{{ asset('assets/js/script.js') }}?v={{ time() }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fabCart = document.getElementById('fab-cart');
            const closeCart = document.getElementById('close-pos-cart');
            const cartPanel = document.getElementById('pos-cart-panel');
            const overlay = document.getElementById('pos-cart-overlay');

            function toggleCart() {
                cartPanel.classList.toggle('translate-x-full');
                overlay.classList.toggle('hidden');
            }

            if(fabCart) fabCart.addEventListener('click', toggleCart);
            if(closeCart) closeCart.addEventListener('click', toggleCart);
            if(overlay) overlay.addEventListener('click', toggleCart);

            // Settings Menu Mobile Logic
            const settingsBtn = document.getElementById('mobile-settings-btn');
            const closeSettings = document.getElementById('close-settings');
            const settingsPanel = document.getElementById('settings-panel');
            const settingsOverlay = document.getElementById('settings-overlay');

            function toggleSettings() {
                settingsPanel.classList.toggle('translate-x-full');
                settingsOverlay.classList.toggle('hidden');
            }

            if(settingsBtn) settingsBtn.addEventListener('click', toggleSettings);
            if(closeSettings) closeSettings.addEventListener('click', toggleSettings);
            if(settingsOverlay) settingsOverlay.addEventListener('click', toggleSettings);
        });
    </script>
</body>
</html>
