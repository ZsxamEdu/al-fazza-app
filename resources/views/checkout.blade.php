@extends('layouts.main')

@section('content')
    <div class="max-w-6xl my-10 mx-auto px-5 flex flex-col lg:flex-row gap-8 items-start">
        <div class="flex-2 bg-white p-8 rounded-lg shadow-[0_2px_10px_rgba(0,0,0,0.05)] w-full">
            <h2 class="text-primary-brown border-b-2 border-border-light pb-2.5 mb-5 mt-0 font-bold text-2xl">Informasi Pemesan</h2>
            <form id="checkoutForm" class="text-sm">
                
                <div class="flex gap-4 mb-6 flex-wrap text-sm">
                    <div class="flex-1 min-w-48 mb-4">
                        <label class="block mb-1.5 font-medium text-sm">Nama Pemesan *</label>
                        <input type="text" id="nama" required placeholder="Masukkan nama lengkap" class="form-input">
                    </div>
                    
                    <div class="flex-1 min-w-48 mb-4">
                        <label class="block mb-1.5 font-medium text-sm">Email *</label>
                        <input type="email" id="email" required placeholder="Masukkan email aktif" class="form-input">
                    </div>
                    
                    <div class="flex-1 min-w-48 mb-4">
                        <label class="block mb-1.5 font-medium text-sm">No. WhatsApp *</label>
                        <input type="tel" id="nohp" required placeholder="Contoh: 081234567890" class="form-input" maxlength="13" oninput="updateCharCount('nohp', 13)">
                        <div class="text-right text-xs text-gray-400 mt-1"><span id="nohp-count">0</span>/13 karakter</div>
                    </div>
                </div>

                <h2 class="text-primary-brown border-b-2 border-border-light pb-2.5 mb-5 mt-8 font-bold text-2xl">Informasi Pengiriman</h2>
                
                <div class="mb-5">
                    <label class="block mb-1.5 font-medium text-sm">Tanggal Pengiriman *</label>
                    <input type="date" id="tanggal_kirim" required min="{{ date('Y-m-d') }}" class="form-input">
                </div>
                
                <div class="mb-6">
                    <label class="block mb-1.5 font-medium text-sm">Detail Alamat Pengiriman *</label>
                    <textarea id="alamat" rows="4" required maxlength="300" placeholder="Isi dengan detail tambahan seperti nomor rumah, blok, nama gedung, patokan, dll." class="form-input" oninput="updateCharCount('alamat', 300)"></textarea>
                    <div class="text-right text-xs text-gray-400 mt-1"><span id="alamat-count">0</span>/300 karakter</div>
                </div>

                <div class="flex items-center gap-2.5 bg-green-100 p-4 rounded-lg text-success mt-5">
                    <i class="fa-solid fa-shield-halved text-2xl"></i>
                    <p class="m-0 text-sm leading-normal">Pembayaran diproses secara aman dan otomatis melalui Midtrans. <strong>Invoice akan dikirimkan ke Email Anda.</strong></p>
                </div>

                <div class="flex items-center gap-2.5 bg-yellow-100 p-4 rounded-lg text-warning-dark mt-4">
                    <i class="fa-solid fa-triangle-exclamation text-2xl"></i>
                    <p class="m-0 text-sm leading-normal"><strong>Peringatan:</strong> Mohon pastikan memilih metode pembayaran yang tepat. Metode pembayaran tidak dapat diubah setelah Anda melanjutkan ke halaman Midtrans.</p>
                </div>
                
            </form>
        </div>

        <div class="flex-1 sticky top-5 w-full">
            <div class="bg-white rounded-lg overflow-hidden shadow-[0_2px_10px_rgba(0,0,0,0.05)] mb-5">
                <div class="bg-primary-brown text-white p-4">
                    <h3 class="m-0 text-lg font-bold">Ringkasan Pesanan</h3>
                </div>
                
                <div class="p-5 border-b border-dashed border-border-light" id="checkout-order-list">
                    </div>

                <div class="p-5">
                    <div class="flex justify-between mb-2.5 text-sm">
                        <span>Total Harga</span>
                        <span id="checkout-subtotal">Rp 0</span>
                    </div>
                    <div class="flex justify-between text-lg font-bold text-dark-brown mt-4 border-t-2 border-border-light pt-4">
                        <span>Total Bayar</span>
                        <span id="checkout-grandtotal">Rp 0</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg overflow-hidden shadow-[0_2px_10px_rgba(0,0,0,0.05)] mb-5">
                <div class="bg-primary-brown text-white p-4">
                    <h3 class="m-0 text-lg font-bold">Catatan</h3>
                </div>
                <textarea id="catatan" rows="3" maxlength="200" placeholder="Tulis catatan pesanan (Opsional)" class="w-full border-none p-5 resize-none outline-none font-inherit" oninput="updateCharCount('catatan', 200)"></textarea>
                <div class="text-right text-xs text-gray-400 px-5 pb-3"><span id="catatan-count">0</span>/200 karakter</div>
            </div>

            <button type="button" class="w-full bg-btn-navy text-white p-4 border-none rounded-lg text-lg font-bold cursor-pointer transition-colors duration-300 hover:bg-btn-navy-hover flex justify-center items-center gap-2.5 shadow-[0_4px_6px_rgba(0,0,0,0.1)] mt-5" onclick="payNow()">
                <i class="fa-solid fa-credit-card"></i> PESAN & BAYAR SEKARANG
            </button>
        </div>
    </div>

    <script src="https://app{{ config('midtrans.is_production') ? '' : '.sandbox' }}.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            if (typeof renderCheckoutSummary === "function") {
                renderCheckoutSummary();
            }
        });
        const noHpInput = document.getElementById('nohp');

        if (noHpInput) {
            // Event 'input' berjalan setiap kali ada karakter yang diketik
            noHpInput.addEventListener("input", function (e) {
                // Hapus SEMUA karakter selain angka (0-9) secara instan
                this.value = this.value.replace(/[^0-9]/g, '');
            });
        }
    </script>
@endsection