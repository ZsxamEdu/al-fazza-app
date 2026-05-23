@extends('layouts.main')

@section('content')
<div style="max-width: 500px; margin: 50px auto; padding: 20px;">
    
    <div style="background: white; border-radius: 15px; padding: 30px; text-align: center; box-shadow: 0 4px 15px rgba(0,0,0,0.1); border-top: 8px solid {{ $ui['color'] }};">
        
        <div style="width: 80px; height: 80px; background: {{ $ui['bg_color'] }}; color: {{ $ui['color'] }}; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 2.5rem;">
            <i class="fa-solid {{ $ui['icon'] }}"></i>
        </div>

        <h2 style="color: {{ $ui['color'] }}; margin-bottom: 5px;">{{ $ui['title'] }}</h2>
        <p style="color: #666; font-size: 0.95rem; margin-bottom: 25px;">{{ $ui['message'] }}</p>

        <div style="background: #f8f9fa; border-radius: 10px; padding: 20px; text-align: left; margin-bottom: 25px; font-size: 0.9rem; line-height: 1.6;">
            <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed #ddd; padding-bottom: 8px; margin-bottom: 8px;">
                <span style="color: #888;">No. Invoice</span>
                <strong style="color: #333;">{{ $transaksi->invoice_number }}</strong>
            </div>
            <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed #ddd; padding-bottom: 8px; margin-bottom: 8px;">
                <span style="color: #888;">Metode Pembayaran</span>
                <span style="font-weight: 500; text-transform: uppercase;">{{ $transaksi->payment_method ?? 'Midtrans' }}</span>
            </div>
            <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed #ddd; padding-bottom: 8px; margin-bottom: 8px;">
                <span style="color: #888;">Status</span>
                <span style="background: {{ $ui['bg_color'] }}; color: {{ $ui['color'] }}; padding: 2px 10px; border-radius: 20px; font-size: 0.8rem; font-weight: bold;">{{ $ui['badge'] }}</span>
            </div>
            <div style="display: flex; justify-content: space-between; margin-top: 15px; font-size: 1.1rem;">
                <strong>Total Bayar</strong>
                <strong style="color: #a67c52;">Rp {{ number_format($transaksi->total_amount, 0, ',', '.') }}</strong>
            </div>
        </div>

        <div style="display: flex; flex-direction: column; gap: 10px;">
            @if($transaksi->payment_status == 'pending' && !empty($transaksi->snap_token))
                <button type="button" onclick="bukaMidtransUlang('{{ $transaksi->snap_token }}')" style="background: #ef6c00; color: white; padding: 12px; border-radius: 8px; border: none; font-weight: bold; cursor: pointer; font-size: 1rem; width: 100%; transition: 0.3s; margin-bottom: 5px;">
                    <i class="fa-solid fa-wallet"></i> LANJUTKAN PEMBAYARAN
                </button>
            @endif

            <a href="/" style="background: #a67c52; color: white; padding: 12px; border-radius: 8px; text-decoration: none; font-weight: bold; transition: 0.3s; text-align: center; display: block;">
                <i class="fa-solid fa-house"></i> Kembali ke Beranda
            </a>
        </div>

    </div>
</div>

@if($transaksi->payment_status == 'pending' && !empty($transaksi->snap_token))
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    
    <script>
        function bukaMidtransUlang(token) {
            window.snap.pay(token, {
                onSuccess: function(result) {
                    location.reload(); // Refresh halaman agar UI berubah jadi HIJAU/LUNAS
                },
                onPending: function(result) {
                    location.reload();
                },
                onError: function(result) {
                    alert("Pembayaran gagal diproses!");
                },
                onClose: function() {
                    console.log('User menutup popup.');
                }
            });
        }
    </script>
@endif
@endsection