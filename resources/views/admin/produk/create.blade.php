@extends('layouts.admin')

@section('title', 'Tambah Produk')

@section('content')
    <div class="flex justify-between items-center mb-8">
        <h1>Tambah Produk Baru</h1>
        <p>Masukkan detail roti atau kue baru yang akan dijual.</p>
    </div>

    <div class="bg-white p-8 rounded-lg shadow-sm">
        <form action="{{ route('admin.produk.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                <div class="flex flex-col gap-2">
                    <label>Nama Roti</label>
                    <input type="text" name="nama" class="form-input" placeholder="Contoh: Bolu Coklat Lumer" value="{{ old('nama') }}" required>
                    @error('nama') <span class="text-danger text-sm mt-1">{{ $message }}</span> @enderror
                </div>
                
                <div class="flex flex-col gap-2">
                    <label>Kategori</label>
                    <select name="kategori" class="form-input" required>
                        <option value="">-- Pilih Kategori --</option>
                        <option value="bolu" {{ old('kategori') == 'bolu' ? 'selected' : '' }}>Bolu</option>
                        <option value="cookies" {{ old('kategori') == 'cookies' ? 'selected' : '' }}>Cookies</option>
                        <option value="pastry" {{ old('kategori') == 'pastry' ? 'selected' : '' }}>Pastry</option>
                        <option value="roti" {{ old('kategori') == 'roti' ? 'selected' : '' }}>Roti</option>
                    </select>
                    @error('kategori') <span class="text-danger text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="flex flex-col gap-2">
                    <label>Tipe Produk</label>
                    <input type="text" name="tipe" class="form-input" placeholder="Contoh: Kue Bolu / Kue Kering" value="{{ old('tipe') }}" required>
                    @error('tipe') <span class="text-danger text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="flex flex-col gap-2">
                    <label>Harga (Rp)</label>
                    <input type="number" name="harga" class="form-input" placeholder="Contoh: 45000" min="0" value="{{ old('harga') }}" required>
                    @error('harga') <span class="text-danger text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="flex flex-col gap-2">
                    <label>Stok Awal</label>
                    <input type="number" name="stok" class="form-input" placeholder="Contoh: 50" min="0" value="{{ old('stok') }}" required>
                    @error('stok') <span class="text-danger text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="flex flex-col gap-2">
                    <label>Foto Produk</label>
                    <input type="file" name="gambar" class="form-input" accept="image/png, image/jpeg, image/jpg" required>
                    <small class="text-text-muted mt-1">Format: JPG, JPEG, PNG. Maksimal 2MB.</small>
                    @error('gambar') <span class="text-danger text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="flex flex-col gap-2 col-span-1 md:col-span-2">
                    <label>Bahan-Bahan Utama</label>
                    <input type="text" name="bahan" class="form-input" placeholder="Contoh: Tepung Terigu, Coklat, Telur, Mentega" value="{{ old('bahan') }}" required>
                    @error('bahan') <span class="text-danger text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="flex flex-col gap-2 col-span-1 md:col-span-2">
                    <label>Deskripsi Produk</label>
                    <textarea name="deskripsi" class="form-input" placeholder="Tuliskan deskripsi menarik tentang roti ini..." required>{{ old('deskripsi') }}</textarea>
                    @error('deskripsi') <span class="text-danger text-sm mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="mt-8 text-right">
                <a href="{{ route('admin.produk.index') }}" class="bg-gray-400 text-white py-3 px-6 rounded-md font-bold hover:bg-gray-500 transition mr-2.5 inline-block no-underline">Batal</a>
                <button type="submit" class="bg-primary-brown text-white py-3 px-6 rounded-md font-bold hover:bg-dark-brown transition cursor-pointer border-none text-base"><i class="fa-solid fa-save"></i> Simpan Produk</button>
            </div>
        </form>
    </div>
@endsection