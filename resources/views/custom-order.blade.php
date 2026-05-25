@extends('layouts.main')

@section('content')
    <div class="checkout-container">
        <div class="checkout-form-section">
            <h2>Informasi Pemesan</h2>
            <form id="customOrderForm">
                <div class="form-row" style="display: flex; flex-direction: row; gap: 15px; margin-bottom: 20px; width: 100%;">
                    
                    <div class="form-group" style="flex: 1;">
                        <label style="font-weight: bold; color: #555; display: block; margin-bottom: 5px;">Nama Lengkap *</label>
                        <input type="text" id="co_nama" required placeholder="Masukkan nama Anda" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px; box-sizing: border-box;">
                    </div>

                    <div class="form-group" style="flex: 1;">
                        <label style="font-weight: bold; color: #555; display: block; margin-bottom: 5px;">Email *</label>
                        <input type="email" id="co_email" required placeholder="Masukkan email aktif" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px; box-sizing: border-box;">
                    </div>

                    <div class="form-group" style="flex: 1;">
                        <label style="font-weight: bold; color: #555; display: block; margin-bottom: 5px;">No. WhatsApp *</label>
                        <input type="number" id="co_nohp" required placeholder="Contoh: 08123456789" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px; box-sizing: border-box;">
                    </div>

                </div>

                <h2>Spesifikasi Kue</h2>
                <div class="form-row">
                    <div class="form-group">
                        <label>Ukuran Kue *</label>
                        <select id="co_ukuran" required>
                            <option value="" disabled selected>Pilih Ukuran</option>
                            <option value="16 cm">16 cm</option>
                            <option value="18 cm">18 cm</option>
                            <option value="20 cm">20 cm</option>
                            <option value="22 cm">22 cm</option>
                            <option value="24 cm">24 cm</option>
                            <option value="30 cm">30 cm</option>
                        </select>
                    </div>
                                        <div class="form-group">
                        <label>Bentuk Kue *</label>
                        <select id="co_bentuk" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                            <option value="Bulat">Bulat</option>
                            <option value="Kotak">Kotak</option>
                            <option value="Hati (Heart)">Hati</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Base Cake (Rasa Bolu) *</label>
                        <select id="co_rasa" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                            <option value="Bolu Coklat">Bolu Coklat</option>
                            <option value="Bolu Pandan">Bolu Pandan</option>
                            <option value="Bolu Vanilla">Bolu Vanilla</option>
                            <option value="Bolu Mocca">Bolu Mocca</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Filling / Isian *</label>
                        <select id="co_isian" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                            <option value="Selai Strawberry">Selai Strawberry</option>
                            <option value="Selai Blueberry">Selai Blueberry</option>
                            <option value="Coklat Ganache">Coklat Ganache</option>
                            <option value="Cream Cheese">Cream Cheese</option>
                        </select>
                    </div>
                </div>

                <h2>Detail Desain</h2>
                <div class="form-group">
                    <label>Tema & Warna Dominan *</label>
                    <input type="text" id="co_tema" required placeholder="Contoh: Tema Spiderman, dominan merah dan biru">
                </div>
                <div class="form-group">
                    <label>Tulisan di Atas Kue *</label>
                    <input type="text" id="co_tulisan" required placeholder="Contoh: Happy Birthday Mama ke-50">
                </div>

                <h2>Waktu & Pengiriman</h2>
                <div class="form-row">
                    <div class="form-group">
                        <label>Tanggal Diperlukan *</label>
                        <input type="date" id="co_tanggal" required>
                    </div>
                    <div class="form-group">
                        <label>Metode *</label>
                        <select id="co_metode" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;" onchange="toggleAlamatCustom()">
                            <option value="Ambil di Toko">Ambil di Toko</option>
                            <option value="Dikirim">Dikirim ke Alamat</option>
                        </select>
                    </div>
                </div>
                <div class="form-group" id="co_alamat_group" style="display: none;">
                    <label>Alamat Pengiriman</label>
                    <textarea id="co_alamat" rows="3" placeholder="Isi detail alamat pengiriman jika memilih 'Dikirim'"></textarea>
                </div>
            </form>
        </div>

        <div class="checkout-summary-section">
            <div class="summary-card">
                <div class="summary-header">
                    <h3>Cara Pesan Custom Cake</h3>
                </div>
                
                <div class="instruction-box">
                    <ol style="line-height: 1.8; color: #555;">
                        <li>Isi formulir spesifikasi <em>Custom Cake</em> Anda dengan lengkap dan teliti.</li>
                        <li><strong>Total harga</strong> akan otomatis terhitung berdasarkan <strong>Ukuran Kue</strong> yang Anda pilih.</li>
                        <li>Klik tombol <strong>"PESAN & BAYAR SEKARANG"</strong> untuk menyelesaikan pembayaran pesanan Anda.</li>
                        <li>Setelah pembayaran berhasil, <strong>Anda bisa mengirimkan foto referensi atau contoh desain kue</strong> Anda ke WhatsApp admin kami <strong>08952338283</strong> dengan melampirkan Nomor Invoice.</li>
                    </ol>
                </div>
            </div>

            <div class="summary-card note-card">
                <div class="summary-header">
                    <h3>Catatan Tambahan</h3>
                </div>
                <textarea id="co_catatan" rows="3" placeholder="Contoh: Tolong krimnya jangan terlalu manis, dll."></textarea>
            </div>

            <button type="button" onclick="prosesCustomOrderMidtrans()" style="background: #1a365d; color: white; border: none; padding: 15px 20px; border-radius: 8px; font-size: 1.1rem; font-weight: bold; width: 100%; cursor: pointer; display: flex; justify-content: center; align-items: center; gap: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); margin-top: 20px;">
                <i class="fa-solid fa-credit-card"></i> PESAN & BAYAR SEKARANG
            </button>
        </div>
    </div>
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
@endsection