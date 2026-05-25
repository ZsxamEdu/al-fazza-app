@extends('layouts.main')

@section('content')
    <div class="checkout-container">
        <div class="checkout-form-section">
            <h2>Informasi Pemesan</h2>
            <form id="checkoutForm">
                <div class="form-row">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Nama Pemesan *</label>
                            <input type="text" id="nama" required placeholder="Masukkan nama lengkap">
                        </div>
                        <div class="form-group">
                            <label>Email *</label>
                            <input type="email" id="email" required placeholder="Masukkan email aktif">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Nomor HP / WhatsApp *</label>
                        <input type="tel" id="nohp" required placeholder="Contoh: 081234567890">
                    </div>
                </div>

                <h2>Informasi Pengiriman</h2>
                <div class="form-group">
                    <label>Waktu Pengiriman *</label>
                    <input type="date" id="tanggal_kirim" required min="{{ date('Y-m-d') }}" ...>
                </div>
                <div class="form-group">
                    <label>Detail Alamat Pengiriman *</label>
                    <textarea id="alamat" rows="4" required placeholder="Isi dengan detail tambahan seperti nomor rumah, blok, nama gedung, patokan, dll."></textarea>
                </div>

                <div class="payment-info">
                    <i class="fa-brands fa-whatsapp"></i>
                    <p>Pembayaran dan konfirmasi pesanan akan dilakukan melalui admin WhatsApp AL-Fazza Bakery.</p>
                </div>
            </form>
        </div>

        <div class="checkout-summary-section">
            <div class="summary-card">
                <div class="summary-header">
                    <h3>Ringkasan Pesanan</h3>
                </div>
                
                <div class="order-list" id="checkout-order-list">
                    </div>

                <div class="summary-totals">
                    <div class="total-row">
                        <span>Total Harga</span>
                        <span id="checkout-subtotal">Rp 0</span>
                    </div>
                    <div class="total-row grand-total">
                        <span>Total Bayar</span>
                        <span id="checkout-grandtotal">Rp 0</span>
                    </div>
                </div>
            </div>

            <div class="summary-card note-card">
                <div class="summary-header">
                    <h3>Catatan</h3>
                </div>
                <textarea id="catatan" rows="3" placeholder="Tulis catatan pesanan di sini (Opsional)"></textarea>
            </div>

            <button type="button" class="btn-bayar-wa" onclick="payNow()" style="background-color: #1a365d;">
                <i class="fa-solid fa-credit-card"></i> BAYAR SEKARANG
            </button>
        </div>
    </div>

    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", renderCheckoutSummary);
    </script>
@endsection