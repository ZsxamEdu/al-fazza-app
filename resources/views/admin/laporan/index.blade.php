@extends('layouts.admin')

@section('title', 'Laporan Keuangan')

@section('content')
    <div class="header-action">
        <div>
            <h1>Laporan Penjualan</h1>
            <p>Ringkasan semua transaksi sukses dan pendapatan toko.</p>
        </div>
        <a href="{{ route('admin.laporan.pdf', request()->all()) }}" target="_blank" class="btn-tambah" style="background-color: #1a365d; color: white; text-decoration: none;">
            <i class="fa-solid fa-file-pdf"></i> Cetak PDF
        </a>
    </div>

    <div class="filter-container" style="background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
        <form action="{{ route('admin.laporan.index') }}" method="GET" style="display: flex; gap: 15px; align-items: flex-end; flex-wrap: wrap;">
            
            <div style="flex: 1; min-width: 200px;">
                <label>Cari Invoice:</label>
                <input type="text" name="search" class="form-control" placeholder="No Invoice..." value="{{ request('search') }}" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
            </div>
            <div style="flex: 1; min-width: 150px;">
                <label>Filter Waktu:</label>
                <select name="range" class="form-control" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                    <option value="">Semua Waktu</option>
                    <option value="minggu" {{ request('range') == 'minggu' ? 'selected' : '' }}>Minggu Ini</option>
                    <option value="bulan" {{ request('range') == 'bulan' ? 'selected' : '' }}>Bulan Ini</option>
                    <option value="tahun" {{ request('range') == 'tahun' ? 'selected' : '' }}>Tahun Ini</option>
                </select>
            </div>
            <div style="flex: 1; min-width: 150px;">
                <label>Mulai Tanggal:</label>
                <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
            </div>
            <div style="flex: 1; min-width: 150px;">
                <label>Sampai Tanggal:</label>
                <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
            </div>
            <div>
                <button type="submit" class="btn-tambah" style="padding: 10px 20px;"><i class="fa-solid fa-filter"></i> Filter</button>
                <a href="{{ route('admin.laporan.index') }}" class="btn-tambah" style="background: #e2e8f0; color: #333; text-decoration: none; padding: 10px 20px;">Reset</a>
            </div>
        </form>
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
