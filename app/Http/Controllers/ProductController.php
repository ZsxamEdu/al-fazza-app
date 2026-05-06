<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index() {
        // Ambil 6 produk terbaru untuk ditampilkan di halaman depan
        $products = Product::limit(6)->get(); 
        return view('index', compact('products'));
    }

    public function kategori(Request $request) {
        $jenis = $request->query('jenis'); // Menangkap '?jenis=bolu' dari URL
        $products = Product::where('kategori', $jenis)->get();
        $judul = 'Aneka ' . ucfirst($jenis);
        
        return view('kategori', compact('products', 'judul'));
    }

    public function detail($id) {
        // Cari produk berdasarkan ID
        $kue = Product::findOrFail($id);
        $rekomendasi = Product::where('id', '!=', $id)->inRandomOrder()->limit(4)->get();
        return view('detail', compact('kue', 'rekomendasi'));
    }
}
