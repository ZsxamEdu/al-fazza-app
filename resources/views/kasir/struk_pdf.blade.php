<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk - {{ $transaksi->invoice_number }}</title>
    <style>
        @page { margin: 10px 15px; }
        body { font-family: 'Courier New', Courier, monospace; font-size: 12px; margin: 0; padding: 10px 15px; color: #000; }
        .text-center { text-align: center; }
        .m-0 { margin: 0; }
        .mb-2 { margin-bottom: 10px; }
        .mb-4 { margin-bottom: 20px; }
        .items-list { width: 100%; border-collapse: collapse; }
        .items-list td { padding: 3px 0; }
        .text-right { text-align: right; }
        .border-dashed { border-top: 1px dashed #000; margin: 10px 0; }
        .fw-bold { font-weight: bold; }
        .f-14 { font-size: 14px; }
        table { width: 100%; border-collapse: collapse; }
    </style>
</head>
<body>
    <div class="text-center mb-4">
        <h2 class="m-0">AL-FAZZA BAKERY</h2>
        <p class="m-0">Jl. Edelweis III No.16 blok J2</p>
        <div class="border-dashed"></div>
    </div>

    <div class="mb-4">
        <table>
            <tr><td style="width: 50px;">No</td><td>: {{ $transaksi->invoice_number }}</td></tr>
            <tr><td>Kasir</td><td>: {{ Auth::check() ? Auth::user()->name : 'Kasir' }}</td></tr>
            <tr><td>Tgl</td><td>: {{ $transaksi->created_at->format('d/m/Y H:i') }}</td></tr>
        </table>
        <div class="border-dashed"></div>
    </div>

    <div class="items-list mb-4">
        <table>
            @foreach($transaksi->details as $detail)
            <tr>
                <td colspan="2">{{ $detail->product->nama ?? 'Produk Dihapus' }}</td>
            </tr>
            <tr>
                <td>{{ $detail->qty }} x {{ number_format($detail->price, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </table>
    </div>

    <div class="border-dashed"></div>

    <table class="mb-2">
        <tr>
            <td class="fw-bold f-14">TOTAL :</td>
            <td class="fw-bold f-14 text-right">Rp {{ number_format($transaksi->total_amount, 0, ',', '.') }}</td>
        </tr>
    </table>
    
    <table>
        <tr>
            <td>Metode Bayar :</td>
            <td class="text-right">{{ $transaksi->payment_method }}</td>
        </tr>
        <tr>
            <td>Tunai :</td>
            <td class="text-right">Rp {{ number_format($transaksi->amount_paid, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Kembali :</td>
            <td class="text-right">Rp {{ number_format($transaksi->change_amount, 0, ',', '.') }}</td>
        </tr>
    </table>

    <div class="text-center" style="margin-top: 30px;">
        <p class="m-0">Terima Kasih Atas Kunjungan Anda</p>
        <p class="m-0">~ Happy Eating ~</p>
    </div>
</body>
</html>
