<div style="font-family: Arial, sans-serif; padding: 20px; background: #f9f9f9;">
    <div style="max-width: 500px; margin: auto; background: white; padding: 30px; border-radius: 10px; border-top: 5px solid #2e7d32;">
        
        <h2 style="color: #2e7d32; text-align: center;">Pembayaran Berhasil!</h2>
        <p>Halo, <strong>{{ $transaction->customer_name }}</strong>!</p>
        <p>Terima kasih, pembayaran untuk pesanan Anda di Al-Fazza Bakery telah kami terima dan pesanan sedang diproses.</p>
        
        <div style="background: #f1f1f1; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <p style="margin: 5px 0;"><strong>No. Invoice:</strong> {{ $transaction->invoice_number }}</p>
            <p style="margin: 5px 0;"><strong>Total Bayar:</strong> Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</p>
            <p style="margin: 5px 0; color: #2e7d32;"><strong>Status:</strong> LUNAS</p>
        </div>

        <a href="{{ $linkInvoice }}" style="display: block; width: 100%; text-align: center; background: #2e7d32; color: white; padding: 12px 0; text-decoration: none; border-radius: 5px; font-weight: bold; margin-top: 20px;">
            LIHAT DETAIL PESANAN
        </a>
    </div>
</div>