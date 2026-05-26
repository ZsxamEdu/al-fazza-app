<!DOCTYPE html>
<html>
<head>
    <title>Laporan Keuangan</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        h2 { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table, th, td { border: 1px solid black; }
        th, td { padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .summary { margin-top: 20px; border-top: 2px solid #000; padding-top: 10px; }
        .summary-item { margin-bottom: 10px; font-weight: bold; font-size: 14px; }
    </style>
</head>
<body>

    <h2>Laporan Keuangan Al-Fazza</h2>
    <p>Tanggal Cetak: {{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Waktu</th>
                <th>No. Invoice</th>
                <th>Tipe Pesanan</th>
                <th>Metode Bayar</th>
                <th>Total Belanja</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transaksi as $index => $t)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $t->created_at->format('d M Y, H:i') }}</td>
                <td>{{ $t->invoice_number }}</td>
                <td>{{ $t->order_type == 'kasir' ? 'Kasir (Offline)' : 'Web (Online)' }}</td>
                <td>{{ $t->payment_method ?? 'Cash' }}</td>
                <td>Rp {{ number_format($t->total_amount, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center;">Tidak ada data laporan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="summary">
        <div class="summary-item">Total Transaksi : {{ $totalTransaksi }}</div>
        <div class="summary-item">Total Pendapatan : Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
    </div>

</body>
</html>