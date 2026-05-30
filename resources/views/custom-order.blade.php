@extends('layouts.main')

@section('content')
    <div class="max-w-6xl my-10 mx-auto px-5 flex flex-col md:flex-row gap-8 items-start">
        <div class="flex-2 bg-white p-8 rounded-lg shadow-[0_2px_10px_rgba(0,0,0,0.05)] w-full">
            <h2 class="text-primary-brown border-b-2 border-border-light pb-2.5 mb-5 mt-0 font-bold text-2xl">Informasi Pemesan</h2>
            <form id="customOrderForm" class="text-sm">
                
                <div class="flex gap-4 mb-6 flex-wrap">
                    
                    <div class="flex-1 min-w-48 mb-4">
                        <label class="block mb-1.5 font-medium text-sm text-text-medium">Nama Lengkap *</label>
                        <input type="text" id="co_nama" required placeholder="Masukkan nama (hanya huruf)" class="w-full p-2.5 border border-border-dark rounded-md font-inherit outline-none focus:border-primary-brown">
                    </div>

                    <div class="flex-1 min-w-48 mb-4">
                        <label class="block mb-1.5 font-medium text-sm text-text-medium">Email *</label>
                        <input type="email" id="co_email" required placeholder="Contoh: nama@email.com" class="form-input">
                    </div>

                    <div class="flex-1 min-w-48 mb-4">
                        <label class="block mb-1.5 font-medium text-sm text-text-medium">No. WhatsApp *</label>
                        <input type="text" id="co_nohp" required placeholder="Contoh: 083123456789" class="form-input">
                    </div>

                </div>

                <h2 class="text-primary-brown border-b-2 border-border-light pb-2.5 mb-5 mt-8 font-bold text-2xl">Spesifikasi Kue</h2>
                
                <div class="flex gap-4 mb-4 flex-wrap">
                    <div class="flex-1 min-w-48 mb-4">
                        <label class="block mb-1.5 font-medium text-sm">Ukuran Kue *</label>
                        <select id="co_ukuran" required class="form-input">
                            <option value="" disabled selected>-- Pilih Ukuran --</option>
                            <option value="16 cm">16 cm</option>
                            <option value="18 cm">18 cm</option>
                            <option value="20 cm">20 cm</option>
                            <option value="22 cm">22 cm</option>
                            <option value="24 cm">24 cm</option>
                            <option value="30 cm">30 cm</option>
                        </select>
                    </div>
                    <div class="flex-1 min-w-48 mb-4">
                        <label class="block mb-1.5 font-medium text-sm">Bentuk Kue *</label>
                        <select id="co_bentuk" required class="form-input">
                            <option value="" disabled selected>-- Pilih Bentuk --</option>
                            <option value="Bulat">Bulat</option>
                            <option value="Kotak">Kotak</option>
                            <option value="Hati (Heart)">Hati</option>
                        </select>
                    </div>
                </div>

                <div class="flex gap-4 mb-6 flex-wrap">
                    <div class="flex-1 min-w-48 mb-4">
                        <label class="block mb-1.5 font-medium text-sm">Base Cake (Rasa) *</label>
                        <select id="co_rasa" required class="form-input">
                            <option value="" disabled selected>-- Pilih Rasa --</option>
                            <option value="Bolu Coklat">Bolu Coklat</option>
                            <option value="Bolu Pandan">Bolu Pandan</option>
                            <option value="Bolu Vanilla">Bolu Vanilla</option>
                            <option value="Bolu Mocca">Bolu Mocca</option>
                        </select>
                    </div>
                    <div class="flex-1 min-w-48 mb-4">
                        <label class="block mb-1.5 font-medium text-sm">Filling / Isian *</label>
                        <select id="co_isian" required class="form-input">
                            <option value="" disabled selected>-- Pilih Isian --</option>
                            <option value="Selai Strawberry">Selai Strawberry</option>
                            <option value="Selai Blueberry">Selai Blueberry</option>
                            <option value="Coklat Ganache">Coklat Ganache</option>
                            <option value="Cream Cheese">Cream Cheese</option>
                        </select>
                    </div>
                </div>

                <h2 class="text-primary-brown border-b-2 border-border-light pb-2.5 mb-5 mt-8 font-bold text-2xl">Detail Desain</h2>
                
                <div class="mb-3.5">
                    <label class="block mb-1.5 font-medium text-sm">Tema & Warna Dominan *</label>
                    <input type="text" id="co_tema" required placeholder="Contoh: Tema Spiderman, Warna Biru" class="form-input">
                </div>
                <div class="mb-6">
                    <label class="block mb-1.5 font-medium text-sm">Tulisan di Atas Kue *</label>
                    <input type="text" id="co_tulisan" required placeholder="Contoh: Happy Birthday Mama ke-50" class="form-input">
                </div>

                <h2 class="text-primary-brown border-b-2 border-border-light pb-2.5 mb-5 mt-8 font-bold text-2xl">Waktu & Pengiriman</h2>
                
                <div class="flex gap-4 mb-4 flex-wrap">
                    <div class="flex-1 min-w-48 mb-4">
                        <label class="block mb-1.5 font-medium text-sm">Tanggal Diperlukan *</label>
                        <input type="date" id="co_tanggal" required min="{{ date('Y-m-d') }}" class="form-input">
                    </div>
                    <div class="flex-1 min-w-48 mb-4">
                        <label class="block mb-1.5 font-medium text-sm">Metode *</label>
                        <select id="co_metode" class="form-input" onchange="toggleAlamatCustom()">
                            <option value="" disabled selected>-- Metode Pengiriman --</option>
                            <option value="Ambil di Toko">Ambil di Toko</option>
                            <option value="Dikirim">Dikirim ke Alamat</option>
                        </select>
                    </div>
                </div>
                
                <div id="co_alamat_group" class="hidden mb-5">
                    <label class="block mb-1.5 font-medium text-sm">Alamat Pengiriman *</label>
                    <textarea id="co_alamat" rows="4" placeholder="Isi detail alamat seperti patokan, nomor rumah, RT/RW dll." class="form-input"></textarea>
                </div>

                <div class="flex items-center gap-2.5 bg-green-100 p-4 rounded text-success mt-5">
                    <i class="fa-solid fa-shield-halved text-2xl"></i>
                    <p class="m-0 text-sm leading-normal">Pembayaran diproses secara aman dan otomatis melalui Midtrans. <strong>Invoice akan dikirimkan ke Email Anda.</strong></p>
                </div>
            </form>
        </div>

        <div class="flex-1 sticky top-5 w-full">
            <div class="bg-white rounded-lg overflow-hidden shadow-[0_2px_10px_rgba(0,0,0,0.05)] mb-5">
                <div class="bg-primary-brown text-white p-4">
                    <h3 class="m-0 text-lg font-bold">Cara Pesan Custom Cake</h3>
                </div>
                
                <div class="p-3.5 rounded-lg">
                    <ol class="leading-[1.8] text-text-medium pl-4 m-0">
                        <li>Isi formulir spesifikasi <em>Custom Cake</em> Anda dengan lengkap.</li>
                        <li><strong>Total harga</strong> otomatis terhitung berdasarkan <strong>Ukuran Kue</strong>.</li>
                        <li>Klik <strong>"PESAN & BAYAR SEKARANG"</strong> untuk melakukan pembayaran otomatis via Midtrans.</li>
                        <li>Setelah lunas, harap <strong>kirimkan foto desain referensi</strong> Anda ke WhatsApp <strong>08952338283</strong> dengan melampirkan Nomor Invoice.</li>
                    </ol>
                </div>
            </div>
            <div class="bg-white rounded-lg overflow-hidden shadow-[0_2px_10px_rgba(0,0,0,0.05)] mb-5">
                <div class="bg-primary-brown text-white p-4">
                    <h3 class="m-0 text-lg font-bold">Petunjuk Harga Custom Cake</h3>
                </div>
                
                <div class="p-3.5 rounded-lg">                    
                    <ul class="list-none p-0 m-0 text-text-dark text-base">
                        <li class="flex justify-between py-2 border-b border-dashed border-border-dark">
                            <span>Ukuran 16 cm</span> <strong>Rp 150.000</strong>
                        </li>
                        <li class="flex justify-between py-2 border-b border-dashed border-border-dark">
                            <span>Ukuran 18 cm</span> <strong>Rp 180.000</strong>
                        </li>
                        <li class="flex justify-between py-2 border-b border-dashed border-border-dark">
                            <span>Ukuran 20 cm</span> <strong>Rp 220.000</strong>
                        </li>
                        <li class="flex justify-between py-2 border-b border-dashed border-border-dark">
                            <span>Ukuran 22 cm</span> <strong>Rp 260.000</strong>
                        </li>
                        <li class="flex justify-between py-2 border-b border-dashed border-border-dark">
                            <span>Ukuran 24 cm</span> <strong>Rp 300.000</strong>
                        </li>
                        <li class="flex justify-between py-2">
                            <span>Ukuran 30 cm</span> <strong>Rp 450.000</strong>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="bg-white rounded-lg overflow-hidden shadow-[0_2px_10px_rgba(0,0,0,0.05)] mt-5">
                <div class="bg-primary-brown text-white p-4">
                    <h3 class="m-0 text-lg font-bold">Catatan Tambahan</h3>
                </div>
                <textarea id="co_catatan" rows="3" placeholder="Contoh: Krim jangan terlalu manis, dll." class="w-full p-2.5 border border-border-dark rounded-md font-inherit outline-none focus:border-primary-brown"></textarea>
            </div>

            <button type="button" onclick="prosesCustomOrderMidtrans()" class="w-full bg-btn-navy text-white p-4 border-none rounded-lg text-lg font-bold cursor-pointer transition-colors duration-300 hover:bg-btn-navy-hover flex justify-center items-center gap-2.5 shadow-[0_4px_6px_rgba(0,0,0,0.1)] mt-5">
                <i class="fa-solid fa-credit-card"></i> PESAN & BAYAR SEKARANG
            </button>   
        </div>
    </div>

    <script src="https://app{{ config('midtrans.is_production') ? '' : '.sandbox' }}.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
@endsection