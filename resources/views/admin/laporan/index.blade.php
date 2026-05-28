@extends('layouts.admin')

@section('title', 'Laporan Keuangan')

@section('content')
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1>Laporan Penjualan</h1>
            <p>Ringkasan semua transaksi sukses dan pendapatan toko.</p>
        </div>
        <div>
            <a href="{{ route('admin.laporan.excel', request()->all()) }}" class="bg-success text-white py-2.5 px-5 rounded-md font-bold hover:bg-green-600 transition inline-block mr-2.5 no-underline">
                <i class="fa-solid fa-file-excel"></i> Export CSV
            </a>
            <a href="{{ route('admin.laporan.pdf', request()->all()) }}" target="_blank" class="bg-blue-900 text-white py-2.5 px-5 rounded-md font-bold hover:bg-blue-700 transition inline-block no-underline">
                <i class="fa-solid fa-file-pdf"></i> Cetak PDF
            </a>
        </div>
    </div>

    <div class="bg-white p-5 rounded-lg mb-5 shadow-sm">
        <form action="{{ route('admin.laporan.index') }}" method="GET" class="flex gap-4 items-end flex-wrap">
            
            <div class="flex-1 min-w-48">
                <label>Cari Invoice:</label>
                <input type="text" name="search" class="w-full p-2 border border-border-dark rounded" placeholder="No Invoice..." value="{{ request('search') }}">
            </div>
            <div class="flex-1 min-w-40">
                <label>Filter Waktu:</label>
                <select name="range" class="w-full p-2 border border-border-dark rounded">
                    <option value="">Semua Waktu</option>
                    <option value="minggu" {{ request('range') == 'minggu' ? 'selected' : '' }}>Minggu Ini</option>
                    <option value="bulan" {{ request('range') == 'bulan' ? 'selected' : '' }}>Bulan Ini</option>
                    <option value="tahun" {{ request('range') == 'tahun' ? 'selected' : '' }}>Tahun Ini</option>
                </select>
            </div>
            <div class="flex-1 min-w-40">
                <label>Mulai Tanggal:</label>
                <input type="date" name="start_date" class="w-full p-2 border border-border-dark rounded" value="{{ request('start_date') }}">
            </div>
            <div class="flex-1 min-w-40">
                <label>Sampai Tanggal:</label>
                <input type="date" name="end_date" class="w-full p-2 border border-border-dark rounded" value="{{ request('end_date') }}">
            </div>
            <div>
                <button type="submit" class="bg-success text-white py-2.5 px-5 rounded-md font-bold hover:bg-green-600 transition inline-block cursor-pointer"><i class="fa-solid fa-filter"></i> Filter</button>
                <a href="{{ route('admin.laporan.index') }}" class="bg-slate-200 text-text-dark py-2.5 px-5 rounded-md font-bold hover:bg-slate-300 transition inline-block no-underline">Reset</a>
            </div>
        </form>
    </div>

    <div class="flex gap-5 mb-8">
        <div class="bg-white p-5 rounded-lg shadow-sm flex-1 border-l-[5px] border-l-success">
            <h3>Total Pendapatan</h3>
            <div class="text-3xl font-bold text-success">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
        </div>
        <div class="bg-white p-5 rounded-lg shadow-sm flex-1 border-l-[5px] border-l-primary-brown">
            <h3>Total Transaksi</h3>
            <div class="text-3xl font-bold text-dark-brown">{{ $totalTransaksi }} Transaksi</div>
        </div>
    </div>

    <div class="bg-white p-5 rounded-lg shadow-sm overflow-x-auto">
        <table class="w-full border-collapse text-left [&_th]:bg-primary-brown [&_th]:text-white [&_th]:py-3 [&_th]:px-4 [&_td]:py-3 [&_td]:px-4 [&_td]:border-b [&_td]:border-border-light [&_td]:align-middle [&_tr:hover]:bg-gray-50">
            <thead>
                <tr class="hover:bg-gray-50">
                    <th class="bg-primary-brown text-white py-3 px-4">Waktu</th>
                    <th class="bg-primary-brown text-white py-3 px-4">No. Invoice</th>
                    <th class="bg-primary-brown text-white py-3 px-4">Tipe Pesanan</th>
                    <th class="bg-primary-brown text-white py-3 px-4">Metode Bayar</th>
                    <th class="bg-primary-brown text-white py-3 px-4">Total Belanja</th>
                    <th class="bg-primary-brown text-white py-3 px-4">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transaksi as $t)
                <tr class="hover:bg-gray-50">
                    <td class="py-3 px-4 border-b border-border-light align-middle">{{ $t->created_at->format('d M Y, H:i') }}</td>
                    <td class="py-3 px-4 border-b border-border-light align-middle"><strong>{{ $t->invoice_number }}</strong></td>
                    <td class="py-3 px-4 border-b border-border-light align-middle">
                        @if($t->order_type == 'kasir')
                            <span class="text-primary-brown font-bold"><i class="fa-solid fa-store"></i> Kasir (Offline)</span>
                        @elseif($t->order_type == 'custom-order')
                            <span class="text-purple-500 font-bold"><i class="fa-solid fa-cake-candles"></i> Custom Cake</span>
                        @else
                            <span class="text-blue-600 font-bold"><i class="fa-solid fa-globe"></i> Web (Online)</span>
                        @endif
                    </td>
                    <td class="py-3 px-4 border-b border-border-light align-middle">{{ $t->payment_method ?? 'Cash' }}</td>
                    <td class="py-3 px-4 border-b border-border-light align-middle"><strong>Rp {{ number_format($t->total_amount, 0, ',', '.') }}</strong></td>
                    <td class="py-3 px-4 border-b border-border-light align-middle">
                        <span class="py-1 px-2.5 rounded-full text-sm font-bold bg-green-50 text-green-800 inline-block"><i class="fa-solid fa-check"></i> Lunas</span>
                    </td>
                </tr>
                @empty
                <tr class="hover:bg-gray-50">
                    <td colspan="6" class="text-center text-text-light p-8">Belum ada data penjualan yang sukses.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Navigasi Halaman --}}
    <div class="mt-4">
        {{ $transaksi->links('vendor.pagination.admin') }}
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
