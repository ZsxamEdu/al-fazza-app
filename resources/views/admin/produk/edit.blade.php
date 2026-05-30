@extends('layouts.admin')

@section('title', 'Edit Produk')

@section('content')
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <h1 class="text-xl lg:text-3xl font-bold m-0 mb-2">Edit Produk</h1>
        <p>Ubah detail roti atau kue yang sudah ada di sistem.</p>
    </div>

    <div class="bg-white p-8 rounded-lg shadow-sm">
        <form action="{{ route('admin.produk.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT') <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                <div class="flex flex-col gap-2">
                    <label>Nama Roti</label>
                    <input type="text" name="nama" class="form-input" value="{{ old('nama', $product->nama) }}" required>
                    @error('nama') <span class="text-danger text-sm mt-1">{{ $message }}</span> @enderror
                </div>
                
                <div class="flex flex-col gap-2">
                    <label>Kategori</label>
                    <select name="kategori" class="form-input" required>
                        <option value="bolu" {{ old('kategori', $product->kategori) == 'bolu' ? 'selected' : '' }}>Bolu</option>
                        <option value="cookies" {{ old('kategori', $product->kategori) == 'cookies' ? 'selected' : '' }}>Cookies</option>
                        <option value="pastry" {{ old('kategori', $product->kategori) == 'pastry' ? 'selected' : '' }}>Pastry</option>
                        <option value="roti" {{ old('kategori', $product->kategori) == 'roti' ? 'selected' : '' }}>Roti</option>
                    </select>
                    @error('kategori') <span class="text-danger text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="flex flex-col gap-2">
                    <label>Tipe Produk</label>
                    <input type="text" name="tipe" class="form-input" value="{{ old('tipe', $product->tipe) }}" required>
                    @error('tipe') <span class="text-danger text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="flex flex-col gap-2">
                    <label>Harga (Rp)</label>
                    <input type="number" name="harga" class="form-input" min="0" value="{{ old('harga', $product->harga) }}" required>
                    @error('harga') <span class="text-danger text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="flex flex-col gap-2">
                    <label>Stok Saat Ini</label>
                    <div class="form-input bg-gray-100 font-bold text-lg text-text-darker">{{ $product->stok }} pcs</div>
                    <div class="bg-blue-50 border-l-4 border-bg-info p-2 text-sm text-text-info mt-1">
                        <i class="fa-solid fa-circle-info"></i> Informasi: Perubahan stok produk hanya dapat dilakukan melalui menu Kelola Stok.
                    </div>
                </div>

                <div class="flex flex-col gap-2">
                    <label>Foto Produk Baru (Opsional)</label>
                    <input type="file" name="gambar" class="form-input" accept="image/png, image/jpeg, image/jpg">
                    <small class="text-text-muted mt-1">Biarkan kosong jika tidak ingin mengganti foto.</small>
                    @error('gambar') <span class="text-danger text-sm mt-1">{{ $message }}</span> @enderror
                    
                    <div class="mt-2.5">
                        <span class="text-sm text-text-light block mb-1">Foto Saat Ini:</span>
                        <img loading="lazy" src="{{ asset($product->gambar) }}" alt="Foto Lama" class="h-20 rounded border border-border-medium">
                    </div>
                </div>

                <div class="flex flex-col gap-2 col-span-1 md:col-span-2">
                    <label>Bahan-Bahan Utama</label>
                    <input type="text" name="bahan" class="form-input" value="{{ old('bahan', $product->bahan) }}" required>
                    @error('bahan') <span class="text-danger text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="flex flex-col gap-2 col-span-1 md:col-span-2">
                    <label>Deskripsi Produk</label>
                    <textarea name="deskripsi" class="form-input" required>{{ old('deskripsi', $product->deskripsi) }}</textarea>
                    @error('deskripsi') <span class="text-danger text-sm mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="mt-8 text-right">
                <a href="{{ route('admin.produk.index') }}" class="bg-gray-400 text-white py-3 px-6 rounded-md font-bold hover:bg-gray-500 transition mr-2.5 inline-block no-underline">Batal</a>
                <button type="submit" class="bg-primary-brown text-white py-3 px-6 rounded-md font-bold hover:bg-dark-brown transition cursor-pointer border-none text-base"><i class="fa-solid fa-save"></i> Perbarui Produk</button>
            </div>
        </form>
    </div>
@endsection