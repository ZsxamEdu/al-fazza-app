@extends('layouts.admin')

@section('title', 'Riwayat Stok')

@section('content')
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-xl lg:text-3xl font-bold m-0 mb-2">Riwayat Barang Masuk/Keluar</h1>
            <p>Pantau pergerakan stok roti dan kue di sini.</p>
        </div>
        <a href="{{ route('admin.stok.create') }}" class="bg-success text-white py-2.5 px-5 rounded-md font-bold hover:bg-green-700 transition inline-block"><i class="fa-solid fa-plus"></i> Catat Stok Baru</a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-4 rounded-md mb-5 border border-green-200">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif

    <div class="bg-white p-5 rounded-lg shadow-sm overflow-x-auto">
        <table class="w-full border-collapse text-left [&_th]:bg-primary-brown [&_th]:text-white [&_th]:py-3 [&_th]:px-4 [&_td]:py-3 [&_td]:px-4 [&_td]:border-b [&_td]:border-border-light [&_td]:align-middle [&_tr:hover]:bg-gray-50 text-xs lg:text-base whitespace-nowrap lg:whitespace-normal">
            <thead>
                <tr class="hover:bg-gray-50">
                    <th class="bg-primary-brown text-white py-3 px-4">Waktu</th>
                    <th class="bg-primary-brown text-white py-3 px-4">Nama Roti</th>
                    <th class="bg-primary-brown text-white py-3 px-4">Tipe</th>
                    <th class="bg-primary-brown text-white py-3 px-4">Jumlah</th>
                    <th class="bg-primary-brown text-white py-3 px-4">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr class="hover:bg-gray-50">
                    <td class="py-3 px-4 border-b border-border-light align-middle">{{ \Carbon\Carbon::parse($log->created_at)->format('d M Y, H:i') }}</td>
                    <td class="py-3 px-4 border-b border-border-light align-middle">
                        <strong>{{ $log->product->nama }}</strong>
                        @if($log->product->trashed())
                            <span class="text-xs text-red-500 font-normal italic ml-1">(Dihapus)</span>
                        @endif
                    </td>
                    <td class="py-3 px-4 border-b border-border-light align-middle">
                        @if($log->tipe == 'masuk')
                            <span class="py-1 px-2.5 rounded-full text-sm font-bold bg-green-50 text-green-800 inline-block"><i class="fa-solid fa-arrow-down"></i> Masuk</span>
                        @else
                            <span class="py-1 px-2.5 rounded-full text-sm font-bold bg-red-50 text-red-800 inline-block"><i class="fa-solid fa-arrow-up"></i> Keluar</span>
                        @endif
                    </td>
                    <td class="py-3 px-4 border-b border-border-light align-middle"><strong>{{ $log->jumlah }} pcs</strong></td>
                    <td class="py-3 px-4 border-b border-border-light align-middle">{{ $log->keterangan ?? '-' }}</td>
                </tr>
                @empty
                <tr class="hover:bg-gray-50">
                    <td colspan="5" class="text-center text-text-light">Belum ada riwayat stok.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Navigasi Halaman --}}
    <div class="mt-4">
        {{ $logs->links('vendor.pagination.admin') }}
    </div>
@endsection