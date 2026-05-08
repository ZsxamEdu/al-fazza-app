@extends('layouts.admin')

@section('title', 'Laporan Keuangan')

@section('content')
    <div class="header-action">
        <div>
            <h1>Laporan Penjualan</h1>
            <p>Ringkasan semua transaksi sukses dan pendapatan toko.</p>
        </div>
        <button onclick="window.print()" class="btn-tambah" style="background-color: #1a365d;">
            <i class="fa-solid fa-print"></i> Cetak Laporan
        </button>
    </div>

    <div class="card-info-container">
        <div class="card-info border-success">
            <h3>Total Pendapatan</h3>
            <div class="number text-success">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
        </div>
        <div class="card-info">
            <h3>Total Transaksi</h3>
            <div class="number">{{ $totalTransaksi }} Transaksi</div>
        </div>
    </div>

    <div class="table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>No. Invoice</th>
                    <th>Tipe Pesanan</th>
                    <th>Metode Bayar</th>
                    <th>Total Belanja</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transaksi as $t)
                <tr>
                    <td>{{ $t->created_at->format('d M Y, H:i') }}</td>
                    <td><strong>{{ $t->invoice_number }}</strong></td>
                    <td>
                        @if($t->order_type == 'kasir')
                            <span style="color: #a67c52; font-weight: bold;"><i class="fa-solid fa-store"></i> Kasir (Offline)</span>
                        @else
                            <span style="color: #1976d2; font-weight: bold;"><i class="fa-solid fa-globe"></i> Web (Online)</span>
                        @endif
                    </td>
                    <td>{{ $t->payment_method ?? 'Cash' }}</td>
                    <td><strong>Rp {{ number_format($t->total_amount, 0, ',', '.') }}</strong></td>
                    <td>
                        <span class="badge-stok stok-aman"><i class="fa-solid fa-check"></i> Lunas</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: #888; padding: 30px;">Belum ada data penjualan yang sukses.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <style>
        @media print {
            body * { visibility: hidden; }
            .main-content, .main-content * { visibility: visible; }
            .main-content { position: absolute; left: 0; top: 0; width: 100%; padding: 0; }
            .sidebar, .btn-tambah { display: none !important; }
            .card-info-container { page-break-inside: avoid; }
        }
    </style>
@endsection
