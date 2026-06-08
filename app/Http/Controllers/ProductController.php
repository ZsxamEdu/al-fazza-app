<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index() {
        // Ambil 6 produk terbaru untuk ditampilkan di halaman depan
        $products = Product::latest()->limit(6)->get(); 

        // Ambil 4 produk terlaris berdasarkan total qty terjual (via TransactionDetail).
        // Menggunakan withSum() agar kompatibel dengan MySQL strict mode (no GROUP BY issue).
        $terlaris = Product::withSum('details as total_terjual', 'qty')
            ->orderByDesc('total_terjual')
            ->limit(4)
            ->get();

            // foreach($products as $p) {
            //     if($p->nama == 'Cheese Cake') { // Ganti dengan nama produk yang diminta
            //         $p->harga = 15000; // Harga baru
            //     }
            // }

        return view('index', compact('products', 'terlaris'));
    }

    public function kategori(Request $request) {
        $jenis = $request->query('jenis'); // Menangkap '?jenis=bolu' dari URL
        $products = Product::where('kategori', $jenis)->get();
        $judul = 'Aneka ' . ucfirst($jenis);
        
        // foreach($products as $p) {
        //     if($p->nama == 'Lidah Kucing') { // Ganti dengan nama produk yang diminta
        //         $p->harga = 15000; // Harga baru
        //     }
        // }

        return view('kategori', compact('products', 'judul'));
    }

    public function detail($id) {
        // Cari produk berdasarkan ID beserta ulasannya
        $kue = Product::with(['reviews' => function($q) {
            $q->orderBy('created_at', 'desc');
        }, 'reviews.transaction'])->findOrFail($id);
        
        $totalReviews = $kue->reviews->count();
        $ratingCounts = [
            5 => $kue->reviews->where('rating', 5)->count(),
            4 => $kue->reviews->where('rating', 4)->count(),
            3 => $kue->reviews->where('rating', 3)->count(),
            2 => $kue->reviews->where('rating', 2)->count(),
            1 => $kue->reviews->where('rating', 1)->count(),
        ];

        $rekomendasi = Product::where('id', '!=', $id)->inRandomOrder()->limit(4)->get();
        return view('detail', compact('kue', 'rekomendasi', 'totalReviews', 'ratingCounts'));
    }
}
