<div style="font-family: Arial, sans-serif; padding: 20px; background: #f9f9f9;">
    <div style="max-width: 500px; margin: auto; background: white; padding: 30px; border-radius: 10px; border-top: 5px solid #a67c52;">
        <h2 style="color: #a67c52;">Halo, {{ $transaction->customer_name }}!</h2>
        <p>Terima kasih telah memesan di Al-Fazza Bakery. Pesanan Anda telah kami catat.</p>
        
        <div style="background: #f1f1f1; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <p style="margin: 5px 0;"><strong>No. Invoice:</strong> {{ $transaction->invoice_number }}</p>
            <p style="margin: 5px 0;"><strong>Total Bayar:</strong> Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</p>
            <p style="margin: 5px 0; color: #ef6c00;"><strong>Status:</strong> Menunggu Pembayaran</p>
        </div>

        <p>Jika halaman pembayaran tertutup secara tidak sengaja, Anda bisa melanjutkan pembayaran kapan saja dengan mengklik tombol di bawah ini:</p>
        
        <a href="{{ $linkInvoice }}" style="display: block; width: 100%; text-align: center; background: #ef6c00; color: white; padding: 12px 0; text-decoration: none; border-radius: 5px; font-weight: bold; margin-top: 20px;">
            LANJUTKAN PEMBAYARAN
        </a>
    </div>
</div>