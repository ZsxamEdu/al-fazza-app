@extends('layouts.admin')

@section('title', 'Catat Stok Baru')

@section('content')
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <h1 class="text-xl lg:text-3xl font-bold m-0 mb-2">Catat Pergerakan Stok</h1>
        <p>Catat stok roti baru matang (Masuk) atau roti rusak/kadaluarsa (Keluar).</p>
    </div>

    <div class="bg-white p-8 rounded-lg shadow-sm" class="max-w-xl">
        <form action="{{ route('admin.stok.store') }}" method="POST">
            @csrf
            
            <div class="flex flex-col gap-2" class="mb-5">
                <label>Pilih Produk Roti</label>
                <select name="product_id" class="form-input" required>
                    <option value="">-- Klik untuk memilih roti --</option>
                    @foreach($products as $p)
                        <option value="{{ $p->id }}" {{ old('product_id') == $p->id ? 'selected' : '' }}>
                            {{ $p->nama }} (Sisa Stok: {{ $p->stok }})
                        </option>
                    @endforeach
                </select>
                @error('product_id') <span class="text-danger text-sm mt-1">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                <div class="flex flex-col gap-2">
                    <label>Jenis Pergerakan</label>
                    <select name="tipe" class="form-input" required>
                        <option value="masuk" {{ old('tipe') == 'masuk' ? 'selected' : '' }}>Barang Masuk (Produksi Baru)</option>
                        <option value="keluar" {{ old('tipe') == 'keluar' ? 'selected' : '' }}>Barang Keluar (Rusak/Expired)</option>
                    </select>
                    @error('tipe') <span class="text-danger text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="flex flex-col gap-2">
                    <label>Jumlah (pcs)</label>
                    <input type="number" name="jumlah" class="form-input" min="1" value="{{ old('jumlah') }}" placeholder="Contoh: 20" required>
                    @error('jumlah') <span class="text-danger text-sm mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="flex flex-col gap-2 mb-5">
                <label>Keterangan (Opsional)</label>
                <textarea name="keterangan" class="form-input" placeholder="Contoh: Roti baru matang dari oven shift pagi..." class="min-h-20">{{ old('keterangan') }}</textarea>
                @error('keterangan') <span class="text-danger text-sm mt-1">{{ $message }}</span> @enderror
            </div>

            <div class="text-right mt-5">
                <a href="{{ route('admin.stok.index') }}" class="bg-gray-400 text-white py-3 px-6 rounded-md font-bold hover:bg-gray-500 transition mr-2.5 inline-block no-underline">Batal</a>
                <button type="submit" class="bg-primary-brown text-white py-3 px-6 rounded-md font-bold hover:bg-dark-brown transition cursor-pointer border-none text-base"><i class="fa-solid fa-save"></i> Simpan Catatan Stok</button>
            </div>
        </form>
    </div>
@endsection