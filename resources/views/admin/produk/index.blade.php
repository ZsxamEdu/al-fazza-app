@extends('layouts.admin')

@section('title', 'Daftar Produk')

@section('content')
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-xl lg:text-3xl font-bold m-0 mb-2">Daftar Produk</h1>
            <p>Kelola menu roti dan kue Al-Fazza Bakery.</p>
        </div>
        <a href="{{ route('admin.produk.create') }}" class="bg-success text-white py-2.5 px-5 rounded-md font-bold hover:bg-green-700 transition inline-block"><i class="fa-solid fa-plus"></i> Tambah Produk</a>
    </div>

    <!-- Filter Section -->
    <div class="bg-white p-5 rounded-lg mb-5">
        <form action="{{ route('admin.produk.index') }}" method="GET" class="flex gap-4 items-end flex-wrap text-sm lg:text-base">
            
            <div class="flex-1 min-w-40">
                <label>Cari Nama Produk:</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik nama roti..." class="border border-border-medium rounded w-full p-2">
            </div>

            <div class="flex-1 min-w-40">
                <label>Kategori:</label>
                <select name="kategori" class="border border-border-medium rounded w-full p-2">
                    <option value="">Semua Kategori</option>
                    <option value="bolu" {{ request('kategori') == 'bolu' ? 'selected' : '' }}>Bolu</option>
                    <option value="cookies" {{ request('kategori') == 'cookies' ? 'selected' : '' }}>Cookies</option>
                    <option value="pastry" {{ request('kategori') == 'pastry' ? 'selected' : '' }}>Pastry</option>
                    <option value="roti" {{ request('kategori') == 'roti' ? 'selected' : '' }}>Roti</option>
                </select>
            </div>

            <div class="flex items-center gap-2 mb-2">
                <input type="checkbox" id="stok_menipis" name="stok_menipis" value="1" {{ request('stok_menipis') == '1' ? 'checked' : '' }} class="w-4 h-4 text-primary-brown">
                <label for="stok_menipis" class="text-danger font-bold">Stok < 10 (Menipis)</label>
            </div>

            <div>
                <button type="submit" class="bg-success text-white py-2.5 px-5 rounded-md font-bold hover:bg-green-700 transition inline-block"><i class="fa-solid fa-filter"></i> Filter</button>
                <a href="{{ route('admin.produk.index') }}" class="bg-slate-200 text-text-dark py-2.5 px-5 rounded-md font-bold hover:bg-slate-300 transition inline-block no-underline">Reset</a>
            </div>
        </form>
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
                    <th class="bg-primary-brown text-white py-3 px-4">No</th>
                    <th class="bg-primary-brown text-white py-3 px-4">Gambar</th>
                    <th class="bg-primary-brown text-white py-3 px-4">Nama Roti</th>
                    <th class="bg-primary-brown text-white py-3 px-4">Kategori</th>
                    <th class="bg-primary-brown text-white py-3 px-4">Harga</th>
                    <th class="bg-primary-brown text-white py-3 px-4">Stok</th>
                    <th class="bg-primary-brown text-white py-3 px-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $index => $p)
                <tr class="hover:bg-gray-50">
                    <td class="py-3 px-4 border-b border-border-light align-middle">{{ $index + 1 }}</td>
                    <td class="py-3 px-4 border-b border-border-light align-middle"><img loading="lazy" src="{{ asset($p->gambar) }}" alt="{{ $p->nama }}" class="w-16 h-16 object-cover rounded border border-border-medium"></td>
                    <td class="py-3 px-4 border-b border-border-light align-middle"><strong>{{ $p->nama }}</strong><br><small class="text-text-light">{{ $p->tipe }}</small></td>
                    <td class="py-3 px-4 border-b border-border-light align-middle">{{ ucfirst($p->kategori) }}</td>
                    <td class="py-3 px-4 border-b border-border-light align-middle">Rp {{ number_format($p->harga, 0, ',', '.') }}</td>
                    <td class="py-3 px-4 border-b border-border-light align-middle">
                        @if($p->stok < 10)
                            <span class="py-1 px-2.5 rounded-full text-sm font-bold bg-red-50 text-red-800 inline-block">{{ $p->stok }} (Tipis!)</span>
                        @else
                            <span class="py-1 px-2.5 rounded-full text-sm font-bold bg-green-50 text-green-800 inline-block">{{ $p->stok }}</span>
                        @endif
                    </td>
                    <td class="py-3 px-4 border-b border-border-light align-middle">
                        <a href="{{ route('admin.produk.edit', $p->id) }}" class="py-1.5 px-3 rounded text-black text-sm mr-1 inline-block bg-yellow-400 hover:bg-yellow-500 transition" title="Edit"><i class="fa-solid fa-pen"></i></a>
                        <form id="delete-form-{{ $p->id }}" action="{{ route('admin.produk.destroy', $p->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="button" onclick="confirmDelete({{ $p->id }}, '{{ addslashes($p->nama) }}')" class="py-1.5 px-3 rounded text-white text-sm mr-1 inline-block bg-danger hover:bg-red-700 transition cursor-pointer border-none" title="Hapus"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-5">
        {{ $products->links('vendor.pagination.admin') }}
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(id, name) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Produk '" + name + "' akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d32f2f',
            cancelButtonColor: '#888',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                showLoader();
                document.getElementById('delete-form-' + id).submit();
            }
        })
    }
</script>
@endpush