<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\TransactionController;

// 1. Rute Dinamis (Mengambil data roti dari Database menggunakan Controller)
// - Rute untuk halaman utama (Bisa diakses semua orang)
Route::get('/', [ProductController::class, 'index']);
Route::get('/kategori', [ProductController::class, 'kategori']);
Route::get('/detail/{id}', [ProductController::class, 'detail']);

// - RUTE GUEST (Hanya bisa diakses kalau belum login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});
    
// - RUTE BACKEND (Hanya bisa diakses kalau sudah login)
// 1. Rute Umum (Bisa diakses Admin & Kasir)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// 2. Rute Khusus Admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Rute halaman utama dashboard admin
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    
    // Rute untuk mengelola produk
    Route::get('/admin/produk', [AdminController::class, 'produkIndex'])->name('admin.produk.index'); // Tampil Tabel
    Route::get('/admin/produk/tambah', [AdminController::class, 'produkCreate'])->name('admin.produk.create'); // Form Tambah
    Route::post('/admin/produk', [AdminController::class, 'produkStore'])->name('admin.produk.store'); // Proses Simpan
    Route::get('/admin/produk/{id}/edit', [AdminController::class, 'produkEdit'])->name('admin.produk.edit'); // Form Edit
    Route::put('/admin/produk/{id}', [AdminController::class, 'produkUpdate'])->name('admin.produk.update'); // Proses Update
    Route::delete('/admin/produk/{id}', [AdminController::class, 'produkDestroy'])->name('admin.produk.destroy'); // Proses Hapus

    // Rute untuk mengelola stok (Riwayat Stok & Catat Stok)
    Route::get('/admin/stok', [AdminController::class, 'stokIndex'])->name('admin.stok.index'); // Tampil Riwayat
    Route::get('/admin/stok/tambah', [AdminController::class, 'stokCreate'])->name('admin.stok.create'); // Form Catat Stok
    Route::post('/admin/stok', [AdminController::class, 'stokStore'])->name('admin.stok.store'); // Proses Simpan & Update Stok

    Route::get('/admin/laporan', [AdminController::class, 'laporanIndex'])->name('admin.laporan.index');

});

// 3. Rute Khusus Kasir
Route::middleware(['auth', 'role:kasir'])->group(function () {
    Route::get('/kasir/pos', [KasirController::class, 'index'])->name('kasir.pos');
    
    Route::post('/kasir/proses', [KasirController::class, 'prosesPos'])->name('kasir.proses');

    Route::get('/kasir/selesai/{id}', [KasirController::class, 'selesai'])->name('kasir.selesai');
});


// 2. Rute Statis (Hanya menampilkan view dasar, belum butuh data dari database)
Route::get('/about', function () {
    return view('about');
});

Route::get('/checkout', function () {
    return view('checkout');
});
Route::post('/checkout/process', [TransactionController::class, 'processCheckout'])->name('checkout.process');
Route::get('/checkout/invoice/{invoice}', [TransactionController::class, 'checkoutInvoice'])->name('checkout.invoice');
Route::post('/midtrans/callback', [TransactionController::class, 'callback']);
Route::post('/checkout/custom/process', [TransactionController::class, 'processCustomCheckout'])->name('checkout.custom.process');
// Route::middleware(['auth'])->group(function () {
// });

Route::get('/custom-order', function () {
    return view('custom-order');
});