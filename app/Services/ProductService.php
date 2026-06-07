<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\File;

class ProductService
{
    public function storeProduct(array $data, $imageFile = null)
    {
        $gambarPath = '';
        
        if ($imageFile) {
            $namaFile = time() . '-' . $imageFile->getClientOriginalName();
            $kategoriFolder = strtolower($data['kategori']); 
            $targetDirektori = 'assets/img/' . $kategoriFolder;
            
            $imageFile->move(public_path($targetDirektori), $namaFile);
            $gambarPath = $targetDirektori . '/' . $namaFile;
        }

        return Product::create([
            'nama' => $data['nama'],
            'tipe' => $data['tipe'],
            'kategori' => $data['kategori'],
            'harga' => $data['harga'],
            'stok' => $data['stok'],
            'deskripsi' => $data['deskripsi'],
            'bahan' => $data['bahan'],
            'gambar' => $gambarPath,
            'rating' => 4.9, // Default rating
        ]);
    }

    public function updateProduct(Product $product, array $data, $imageFile = null)
    {
        $gambarPath = $product->gambar; 

        if ($imageFile) {
            if (File::exists(public_path($product->gambar))) {
                File::delete(public_path($product->gambar));
            }

            $namaFile = time() . '-' . $imageFile->getClientOriginalName();
            $kategoriFolder = strtolower($data['kategori']); 
            $targetDirektori = 'assets/img/' . $kategoriFolder;
            
            $imageFile->move(public_path($targetDirektori), $namaFile);
            $gambarPath = $targetDirektori . '/' . $namaFile;
        }

        $product->update([
            'nama' => $data['nama'],
            'tipe' => $data['tipe'],
            'kategori' => $data['kategori'],
            'harga' => $data['harga'],
            'deskripsi' => $data['deskripsi'],
            'bahan' => $data['bahan'],
            'gambar' => $gambarPath,
        ]);

        return $product;
    }

    public function deleteProduct(Product $product)
    {
        // Karena menggunakan SoftDeletes, file gambar fisik tidak dihapus
        // agar foto produk di riwayat invoice lama (pelanggan) tidak rusak.
        $product->delete();
    }
}
