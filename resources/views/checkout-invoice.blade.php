@extends('layouts.main')

@section('content')
<div class="max-w-lg my-12 mx-auto p-5">
    
    <div class="bg-white rounded-2xl p-8 text-center shadow-[0_4px_15px_rgba(0,0,0,0.1)]" style="border-top: 8px solid {{ $ui['color'] }};">
        
        <div class="w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-5 text-4xl" style="background: {{ $ui['bg_color'] }}; color: {{ $ui['color'] }};">
            <i class="fa-solid {{ $ui['icon'] }}"></i>
        </div>

        <h2 class="mb-1.5 font-bold text-2xl" style="color: {{ $ui['color'] }};">{{ $ui['title'] }}</h2>
        <p class="text-text-muted text-base mb-6">{{ $ui['message'] }}</p>

        <div class="bg-bg-light rounded-xl p-5 text-left mb-6 text-sm leading-[1.6]">
            <div class="flex justify-between border-b border-dashed border-border-medium pb-2 mb-2">
                <span class="text-text-light">No. Invoice</span>
                <strong class="text-text-dark">{{ $transaksi->invoice_number }}</strong>
            </div>
            <div class="flex justify-between border-b border-dashed border-border-medium pb-2 mb-2">
                <span class="text-text-light">Metode Pembayaran</span>
                <span class="font-medium uppercase">{{ $transaksi->payment_method ?? 'Midtrans' }}</span>
            </div>
            <div class="flex justify-between border-b border-dashed border-border-medium pb-2 mb-2">
                <span class="text-text-light">Status</span>
                <span class="py-0.5 px-2.5 rounded-3xl text-xs font-bold" style="background: {{ $ui['bg_color'] }}; color: {{ $ui['color'] }};">{{ $ui['badge'] }}</span>
            </div>
                        <!-- Tambahan Data Pelanggan -->
            <div class="flex justify-between border-b border-dashed border-border-medium pb-2 mb-2 mt-4">
                <span class="text-text-light">Nama Pelanggan</span>
                <strong class="text-text-dark text-right">{{ $transaksi->customer_name }}<br><small class="font-normal">{{ $transaksi->customer_phone }}</small></strong>
            </div>

            @if($transaksi->order_type != 'kasir')
            <div class="flex justify-between border-b border-dashed border-border-medium pb-2 mb-2">
                <span class="text-text-light">Alamat Pengiriman</span>
                <span class="text-text-dark text-right max-w-[60%] text-sm">{{ $transaksi->delivery_address ?? 'Ambil di Toko' }}</span>
            </div>
            @endif

            @if($transaksi->order_type == 'custom-order')
            <div class="flex justify-between border-b border-dashed border-border-medium pb-2 mb-2">
                <span class="text-text-light">Jenis Pesanan</span>
                <span class="bg-badge-purple text-white py-0.5 px-2 rounded text-xs font-bold">Custom Cake</span>
            </div>
            @elseif($transaksi->order_type == 'online')
            <div class="flex justify-between border-b border-dashed border-border-medium pb-2 mb-2">
                <span class="text-text-light">Jenis Pesanan</span>
                <span class="bg-badge-blue text-white py-0.5 px-2 rounded text-xs font-bold">Web (Online)</span>
            </div>
            @endif
            <div class="mb-3.5">
                <strong class="text-text-dark block mb-2.5">Detail Pesanan:</strong>
                @if($transaksi->order_type == 'custom-order')
                    <div class="text-sm text-text-dark bg-gray-50 p-4 rounded-lg border border-border-light leading-relaxed">
                        {!! str_replace(' | ', '<br>', e($transaksi->custom_details)) !!}
                    </div>
                @else
                    @foreach($transaksi->details as $detail)
                        <div class="flex justify-between items-center border-b border-border-light pb-2.5 mb-2.5">
                            <div>
                                <span class="text-text-dark font-medium block">{{ $detail->product->nama }} (x{{ $detail->qty }})</span>
                                <span class="text-text-light text-sm">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</span>
                            </div>
                            @if($transaksi->order_status == 'selesai')
                                @php
                                    $hasReviewed = \App\Models\Review::where('transaction_id', $transaksi->id)
                                        ->where('product_id', $detail->product_id)
                                        ->exists();
                                @endphp
                                @if($hasReviewed)
                                    <span class="bg-success-bg text-text-success py-1 px-2.5 rounded-3xl text-xs font-bold"><i class="fa-solid fa-check"></i> Telah Dinilai</span>
                                @else
                                    <a href="{{ route('review.create', ['invoice' => $transaksi->invoice_number, 'product_id' => $detail->product_id]) }}" class="bg-primary-brown text-white py-1 px-2.5 rounded-3xl text-xs font-bold no-underline inline-block"><i class="fa-solid fa-star"></i> Beri Penilaian</a>
                                @endif
                            @endif
                        </div>
                    @endforeach
                @endif
            </div>

            <div class="flex justify-between mt-4 text-lg">
                <strong>Total Bayar</strong>
                <strong class="text-primary-brown">Rp {{ number_format($transaksi->total_amount, 0, ',', '.') }}</strong>
            </div>
        </div>

        <div class="flex flex-col gap-2.5">
            @if($transaksi->payment_status == 'pending' && !empty($transaksi->snap_token))
                <button type="button" onclick="bukaMidtransUlang('{{ $transaksi->snap_token }}')" class="bg-warning text-white p-3 rounded-lg border-none font-bold cursor-pointer text-base w-full transition-all duration-300 mb-1.5 hover:bg-warning-hover">
                    <i class="fa-solid fa-wallet"></i> LANJUTKAN PEMBAYARAN
                </button>
            @endif

            <a href="/" class="bg-primary-brown text-white p-3 rounded-lg no-underline font-bold transition-all duration-300 text-center block hover:bg-dark-brown">
                <i class="fa-solid fa-house"></i> Kembali ke Beranda
            </a>
        </div>

    </div>
</div>

@if($transaksi->payment_status == 'pending' && !empty($transaksi->snap_token))
    <script src="https://app{{ config('midtrans.is_production') ? '' : '.sandbox' }}.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    
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
                    Swal.fire({ icon: 'error', title: 'Oops...', text: 'Pembayaran gagal diproses!' });
                },
                onClose: function() {
                    console.log('User menutup popup.');
                }
            });
        }
    </script>
@endif
@endsection