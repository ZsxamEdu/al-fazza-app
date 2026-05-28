@extends('layouts.admin')

@section('title', 'Daftar Produk')

@section('content')
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1>Daftar Produk</h1>
            <p>Kelola menu roti dan kue Al-Fazza Bakery.</p>
        </div>
        <a href="{{ route('admin.produk.create') }}" class="bg-success text-white py-2.5 px-5 rounded-md font-bold hover:bg-green-700 transition inline-block"><i class="fa-solid fa-plus"></i> Tambah Produk</a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-4 rounded-md mb-5 border border-green-200">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif

    <div class="bg-white p-5 rounded-lg shadow-sm overflow-x-auto">
        <table class="w-full border-collapse text-left [&_th]:bg-primary-brown [&_th]:text-white [&_th]:py-3 [&_th]:px-4 [&_td]:py-3 [&_td]:px-4 [&_td]:border-b [&_td]:border-border-light [&_td]:align-middle [&_tr:hover]:bg-gray-50">
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
                    <td class="py-3 px-4 border-b border-border-light align-middle"><img src="{{ asset($p->gambar) }}" alt="{{ $p->nama }}" class="w-16 h-16 object-cover rounded border border-border-medium"></td>
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
                document.getElementById('delete-form-' + id).submit();
            }
        })
    }
</script>
@endpush