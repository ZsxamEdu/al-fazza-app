<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\InventoryLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\File; 

class AdminController extends Controller
{
    public function index()
    {
        $totalProduk = Product::count();
        $stokMenipis = Product::where('stok', '<', 10)->get();

        // 1. Hitung Total Penjualan Bulan Ini (Untuk Kartu Info)
        $penjualanBulanIni = Transaction::whereMonth('created_at', Carbon::now()->month)
                                        ->whereYear('created_at', Carbon::now()->year)
                                        ->sum('total_amount'); 

        // 2. Siapkan Data untuk Grafik (7 Hari Terakhir)
        $labels = [];
        $dataPendapatan = [];

        for ($i = 6; $i >= 0; $i--) {
            $tanggal = Carbon::now()->subDays($i);
            $labels[] = $tanggal->translatedFormat('l'); // Format ke nama hari (Senin, Selasa, dll)

            // Ambil total pendapatan di hari tersebut
            $totalHariIni = Transaction::whereDate('created_at', $tanggal->format('Y-m-d'))
                                       ->sum('total_amount');
            $dataPendapatan[] = $totalHariIni;
        }

        return view('admin.dashboard', compact(
            'totalProduk', 'stokMenipis', 'penjualanBulanIni', 'labels', 'dataPendapatan'
        ));
    }
    
    // FUNGSI KELOLA PRODUK (CRUD)
    // Menampilkan tabel produk
    public function produkIndex()
    {
        // Mengambil semua produk, diurutkan dari yang terbaru ditambahkan
        $products = Product::orderBy('id', 'desc')->get();
        return view('admin.produk.index', compact('products'));
    }
    // Menampilkan halaman form tambah produk
    public function produkCreate()
    {
        return view('admin.produk.create');
    }

    // Memproses penyimpanan data ke database
    public function produkStore(Request $request)
    {
        // 1. Validasi data
        $request->validate([
            'nama' => 'required|string|max:255',
            'tipe' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|numeric|min:0',
            'deskripsi' => 'required|string',
            'bahan' => 'required|string',
            'gambar' => 'required|image|mimes:jpeg,png,jpg|max:2048', 
        ]);

        // 2. Proses Upload Gambar Dinamis Berdasarkan Kategori
        $gambarPath = '';
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $namaFile = time() . '-' . $file->getClientOriginalName();
            
            // Ambil nama kategori dari form (misal: 'bolu', 'cookies', 'roti')
            $kategoriFolder = strtolower($request->kategori); 
            
            // Tentukan jalur foldernya sesuai strukturmu: assets/img/{kategori}
            $targetDirektori = 'assets/img/' . $kategoriFolder;
            
            // Pindahkan file ke folder yang sesuai
            $file->move(public_path($targetDirektori), $namaFile);
            
            // Simpan path-nya untuk di database
            $gambarPath = $targetDirektori . '/' . $namaFile;
        }

        // 3. Simpan ke database
        Product::create([
            'nama' => $request->nama,
            'tipe' => $request->tipe,
            'kategori' => $request->kategori,
            'harga' => $request->harga,
            'stok' => $request->stok,
            'deskripsi' => $request->deskripsi,
            'bahan' => $request->bahan,
            'gambar' => $gambarPath,
            'rating' => 4.9,
        ]);

        // 4. Kembali ke tabel dengan pesan sukses
        return redirect()->route('admin.produk.index')->with('success', 'Produk baru berhasil ditambahkan!');
    }

    // Menampilkan halaman form edit dengan data lama
    public function produkEdit($id)
    {
        $product = Product::findOrFail($id); // Cari roti berdasarkan ID
        return view('admin.produk.edit', compact('product'));
    }

    // Memproses perubahan data ke database
    public function produkUpdate(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        // 1. Validasi data (gambar dibuat 'nullable' alias tidak wajib diisi)
        $request->validate([
            'nama' => 'required|string|max:255',
            'tipe' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|numeric|min:0',
            'deskripsi' => 'required|string',
            'bahan' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', 
        ]);

        // 2. Tentukan path gambar (default pakai gambar yang sudah ada)
        $gambarPath = $product->gambar; 

        // Jika admin mengupload gambar baru
        if ($request->hasFile('gambar')) {
            // Hapus file gambar lama dari folder proyek agar tidak menumpuk
            if (File::exists(public_path($product->gambar))) {
                File::delete(public_path($product->gambar));
            }

            // Proses upload gambar baru (sama seperti fitur Create)
            $file = $request->file('gambar');
            $namaFile = time() . '-' . $file->getClientOriginalName();
            $kategoriFolder = strtolower($request->kategori); 
            $targetDirektori = 'assets/img/' . $kategoriFolder;
            
            $file->move(public_path($targetDirektori), $namaFile);
            $gambarPath = $targetDirektori . '/' . $namaFile;
        } elseif ($request->kategori != $product->kategori) {
            // (Opsional tingkat lanjut) Kalau pindah kategori tapi ga ganti foto, 
            // biarkan saja foto di folder lama untuk amannya, atau logic pindah file bisa ditambah di sini.
        }

        // 3. Update data di database
        $product->update([
            'nama' => $request->nama,
            'tipe' => $request->tipe,
            'kategori' => $request->kategori,
            'harga' => $request->harga,
            'stok' => $request->stok,
            'deskripsi' => $request->deskripsi,
            'bahan' => $request->bahan,
            'gambar' => $gambarPath,
        ]);

        // 4. Kembali ke tabel dengan pesan sukses
        return redirect()->route('admin.produk.index')->with('success', 'Data produk berhasil diperbarui!');
    }

    // Memproses penghapusan data dari database
    public function produkDestroy($id)
    {
        // Cari produk berdasarkan ID
        $product = Product::findOrFail($id);

        // Hapus file gambar dari server (folder assets/img/...)
        if (File::exists(public_path($product->gambar))) {
            File::delete(public_path($product->gambar));
        }

        // Hapus data dari database
        $product->delete();

        // Kembalikan ke halaman tabel dengan pesan sukses
        return redirect()->route('admin.produk.index')->with('success', 'Produk beserta fotonya berhasil dihapus!');
    }

    // FUNGSI KELOLA STOK (Riwayat Stok & Catat Stok)
    // 1. Tampilkan riwayat stok masuk & keluar
    public function stokIndex()
    {
        // Ambil data log beserta data produknya, urutkan dari yang terbaru
        $logs = InventoryLog::with('product')->orderBy('created_at', 'desc')->get();
        return view('admin.stok.index', compact('logs'));
    }

    // 2. Tampilkan form untuk mencatat stok
    public function stokCreate()
    {
        // Ambil semua produk untuk ditampilkan di dropdown pilihan
        $products = Product::orderBy('nama', 'asc')->get();
        return view('admin.stok.create', compact('products'));
    }

    // 3. Simpan log dan otomatis UPDATE stok produknya
    public function stokStore(Request $request)
    {
        // Validasi input
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'tipe' => 'required|in:masuk,keluar',
            'jumlah' => 'required|integer|min:1',
            'keterangan' => 'nullable|string|max:255',
        ]);

        // Catat ke tabel inventory_logs
        InventoryLog::create([
            'product_id' => $request->product_id,
            'tipe' => $request->tipe,
            'jumlah' => $request->jumlah,
            'keterangan' => $request->keterangan,
        ]);

        // Cari produknya, lalu tambah/kurangi stok utamanya
        $product = Product::findOrFail($request->product_id);
        
        if ($request->tipe === 'masuk') {
            $product->increment('stok', $request->jumlah); // Otomatis nambah stok
        } else {
            $product->decrement('stok', $request->jumlah); // Otomatis ngurangin stok
        }

        return redirect()->route('admin.stok.index')->with('success', 'Riwayat stok berhasil dicatat dan stok produk telah diperbarui!');
    }

        public function laporanIndex(Request $request)
    {
        $query = Transaction::where('payment_status', 'success');

        // Fitur Pencarian (berdasarkan No. Invoice)
        if ($request->filled('search')) {
            $query->where('invoice_number', 'like', '%' . $request->search . '%');
        }

        // Fitur Filter Rentang Waktu Terpilih (Minggu, Bulan, Tahun)
        if ($request->filled('range')) {
            if ($request->range == 'minggu') {
                $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
            } elseif ($request->range == 'bulan') {
                $query->whereMonth('created_at', Carbon::now()->month)
                      ->whereYear('created_at', Carbon::now()->year);
            } elseif ($request->range == 'tahun') {
                $query->whereYear('created_at', Carbon::now()->year);
            }
        }

        // Fitur Filter Range Tanggal Kustom (Mulai s.d Selesai)
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
        }

        $transaksi = $query->orderBy('created_at', 'desc')->get();

        $totalPendapatan = $transaksi->sum('total_amount');
        $totalTransaksi = $transaksi->count();

        return view('admin.laporan.index', compact('transaksi', 'totalPendapatan', 'totalTransaksi'));
    }

    // Fungsi Baru untuk Cetak PDF
    public function laporanPdf(Request $request)
    {
        $query = Transaction::where('payment_status', 'success');

        // Terapkan filter yang sama untuk PDF
        if ($request->filled('search')) {
            $query->where('invoice_number', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('range')) {
            if ($request->range == 'minggu') {
                $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
            } elseif ($request->range == 'bulan') {
                $query->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year);
            } elseif ($request->range == 'tahun') {
                $query->whereYear('created_at', Carbon::now()->year);
            }
        }
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
        }

        $transaksi = $query->orderBy('created_at', 'desc')->get();
        $totalPendapatan = $transaksi->sum('total_amount');
        $totalTransaksi = $transaksi->count();

        // Load view PDF
        $pdf = Pdf::loadView('admin.laporan.pdf', compact('transaksi', 'totalPendapatan', 'totalTransaksi'));
        
        // Return file PDF
        return $pdf->stream('Laporan_Keuangan_AlFazza.pdf');
    }

}
