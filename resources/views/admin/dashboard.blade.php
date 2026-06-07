@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <h1 class="text-xl lg:text-3xl font-bold m-0 mb-2">Dashboard Ringkasan</h1>
        <p>Selamat datang, <strong>{{ Auth::user()->name }}</strong></p>
    </div>

    @if($stokMenipis->count() > 0)
    <div class="bg-bg-danger-light text-text-danger-dark py-4 px-5 rounded-lg border-l-4 border-l-danger mb-5 font-bold flex items-center gap-2.5">
        <i class="fa-solid fa-triangle-exclamation text-2xl"></i>
        <span>Peringatan Darurat: Terdapat {{ $stokMenipis->count() }} produk yang stoknya hampir habis (< 10). Segera lakukan produksi ulang!</span>
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-3 lg:gap-5 mb-8">
        <div class="bg-white p-5 rounded-lg shadow-sm flex-1 border-l-[5px] border-l-primary-brown">
            <h3 class="text-base lg:text-xl font-bold m-0 mb-2">Total Varian Produk</h3>
            <div class="text-2xl lg:text-3xl font-bold text-dark-brown">{{ $totalProduk }}</div>
        </div>
        <div class="bg-white p-5 rounded-lg shadow-sm flex-1 border-l-[5px] border-l-danger">
            <h3 class="text-base lg:text-xl font-bold m-0 mb-2">Stok Menipis (< 10)</h3>
            <div class="text-2xl lg:text-3xl font-bold text-danger">{{ $stokMenipis->count() }}</div>
        </div>
        <div class="bg-white p-5 rounded-lg shadow-sm flex-1 border-l-[5px] border-l-warning">
            <h3 class="text-lg lg:text-xl font-bold m-0 mb-2">Pesanan Baru (Siap Diproses)</h3>
            <div class="text-xl lg:text-3xl font-bold text-warning">{{ $pesananBaru }}</div>
        </div>
        <div class="bg-white p-5 rounded-lg shadow-sm flex-1 border-l-[5px] border-l-success">
            <h3 class="text-lg lg:text-xl font-bold m-0 mb-2">Total Penjualan Bulan Ini</h3>
            <div class="text-xl lg:text-3xl font-bold text-success">Rp {{ number_format($penjualanBulanIni, 0, ',', '.') }}</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-[1fr_2fr] gap-5">
        <div class="bg-white p-5 rounded-lg shadow-sm">
            <h3 class="text-base lg:text-xl font-bold m-0 mb-2"><i class="fa-solid fa-triangle-exclamation text-danger"></i> Stok Menipis</h3>
            <hr class="border-0 border-t border-border-light my-4">
            @if($stokMenipis->count() > 0)
                <ul class="stock-list">
                    @foreach($stokMenipis as $item)
                        <li class="stock-item">
                            <strong>{{ $item->nama }}</strong> <br> 
                            Sisa: <span class="text-danger font-bold">{{ $item->stok }} pcs</span>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-success"><i class="fa-solid fa-circle-check"></i> Stok aman!</p>
            @endif
        </div>

        <div class="bg-white p-5 rounded-lg shadow-sm">
            <h3 class="text-base lg:text-xl font-bold m-0 mb-2"><i class="fa-solid fa-chart-line text-primary-brown"></i> Pendapatan 7 Hari Terakhir</h3>
            <hr class="border-0 border-t border-border-light my-4">
            <div class="relative h-[250px] w-full">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>

    <script>
        window.chartLabels = @json($labels);
        window.chartData = @json($dataPendapatan);
    </script>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush
