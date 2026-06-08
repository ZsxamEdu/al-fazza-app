<div style="font-family: Arial, sans-serif; padding: 20px; background: #f9f9f9;">
    <div style="max-width: 500px; margin: auto; background: white; padding: 30px; border-radius: 10px; border-top: 5px solid #a67c52;">
        <h2 style="color: #a67c52; text-align: center; margin-top: 0; margin-bottom: 25px;">Al-Fazza Bakery</h2>
        
        <p>Halo, <strong>{{ $transaction->customer_name }}</strong>!</p>
        <p>Ada kabar baik! Status pesanan Anda untuk nomor invoice <strong>{{ $transaction->invoice_number }}</strong> telah diperbarui.</p>
        
        <div style="background: #fff3e0; border-left: 4px solid #ef6c00; padding: 15px; margin: 20px 0; border-radius: 0 8px 8px 0; font-weight: bold; font-size: 18px; text-transform: uppercase; color: #ef6c00;">
            Status Baru: {{ $transaction->order_status }}
        </div>
        
        <div style="background: #f1f1f1; padding: 15px; border-radius: 5px; margin-bottom: 20px; font-size: 14px;">
            <p style="margin: 0 0 10px 0;"><strong>Rincian Pesanan:</strong></p>
            <p style="margin: 5px 0;">Invoice: {{ $transaction->invoice_number }}</p>
            <p style="margin: 5px 0;">Total Belanja: Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</p>
            <p style="margin: 5px 0;">Metode Pembayaran: {{ str_replace('_', ' ', $transaction->payment_method) ?? 'Online' }}</p>
            <p style="margin: 5px 0;">Alamat Pengiriman: {{ $transaction->delivery_address ?? 'Ambil di Toko' }}</p>
        </div>
        
        <p>Untuk melihat rincian pesanan secara lengkap, silakan klik tombol di bawah ini:</p>
        
        <a href="{{ url('/checkout/invoice/' . $transaction->invoice_number . '?token=' . $transaction->token) }}" style="display: block; width: 100%; text-align: center; background: #a67c52; color: white; padding: 12px 0; text-decoration: none; border-radius: 5px; font-weight: bold; margin-top: 20px; box-sizing: border-box;">
            Lihat Invoice Anda
        </a>
        
        <p style="margin-top: 30px; font-size: 14px; text-align: center; color: #888; border-top: 1px solid #eee; padding-top: 20px;">
            Terima kasih telah berbelanja di Al-Fazza Bakery!<br>
            &copy; {{ date('Y') }} Al-Fazza Bakery. Hak Cipta Dilindungi.
        </p>
    </div>
</div>
