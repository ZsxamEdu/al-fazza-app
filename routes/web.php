<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\PaymentCallbackController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\OrderController;

// Rute Dinamis Halaman Utama
Route::get('/', [ProductController::class, 'index']);
Route::get('/kategori', [ProductController::class, 'kategori']);
Route::get('/detail/{id}', [ProductController::class, 'detail']);

// RUTE GUEST (Belum login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
});
    
// RUTE BACKEND UMUM
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// RUTE KHUSUS ADMIN
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Dashboard
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    
    // Kelola Produk
    Route::get('/admin/produk', [AdminProductController::class, 'produkIndex'])->name('admin.produk.index');
    Route::get('/admin/produk/tambah', [AdminProductController::class, 'produkCreate'])->name('admin.produk.create');
    Route::post('/admin/produk', [AdminProductController::class, 'produkStore'])->name('admin.produk.store');
    Route::get('/admin/produk/{id}/edit', [AdminProductController::class, 'produkEdit'])->name('admin.produk.edit');
    Route::put('/admin/produk/{id}', [AdminProductController::class, 'produkUpdate'])->name('admin.produk.update');
    Route::delete('/admin/produk/{id}', [AdminProductController::class, 'produkDestroy'])->name('admin.produk.destroy');

    // Kelola Stok
    Route::get('/admin/stok', [InventoryController::class, 'stokIndex'])->name('admin.stok.index');
    Route::get('/admin/stok/tambah', [InventoryController::class, 'stokCreate'])->name('admin.stok.create');
    Route::post('/admin/stok', [InventoryController::class, 'stokStore'])->name('admin.stok.store');

    // Laporan
    Route::get('/admin/laporan', [ReportController::class, 'laporanIndex'])->name('admin.laporan.index');
    Route::get('/admin/laporan/pdf', [ReportController::class, 'laporanPdf'])->name('admin.laporan.pdf');
    Route::get('/admin/laporan/excel', [ReportController::class, 'laporanExcel'])->name('admin.laporan.excel');

    // Pesanan
    Route::get('/admin/pesanan', [OrderController::class, 'pesananIndex'])->name('admin.pesanan.index');
    Route::put('/admin/pesanan/{id}/status', [OrderController::class, 'pesananUpdateStatus'])->name('admin.pesanan.updateStatus');
});

// RUTE KHUSUS KASIR
Route::middleware(['auth', 'role:kasir'])->group(function () {
    Route::get('/kasir/pos', [KasirController::class, 'index'])->name('kasir.pos');
    Route::post('/kasir/proses', [KasirController::class, 'prosesPos'])->name('kasir.proses');
    Route::get('/kasir/selesai/{id}', [KasirController::class, 'selesai'])->name('kasir.selesai');
    Route::get('/kasir/cetak/{id}', [KasirController::class, 'cetakStruk'])->name('kasir.cetak');
});

// RUTE TRANSAKSI & STATIS
Route::view('/about', 'about');
Route::view('/checkout', 'checkout');
Route::view('/custom-order', 'custom-order');

Route::post('/checkout/process', [TransactionController::class, 'processCheckout'])->name('checkout.process')->middleware('throttle:5,1');
Route::get('/checkout/invoice/{invoice}', [TransactionController::class, 'checkoutInvoice'])->name('checkout.invoice');

// Review Routes
Route::get('/review/{invoice}/{product_id}', [ReviewController::class, 'create'])->name('review.create');
Route::post('/review/{invoice}/{product_id}', [ReviewController::class, 'store'])->name('review.store');

Route::post('/checkout/custom/process', [TransactionController::class, 'processCustomCheckout'])->name('checkout.custom.process')->middleware('throttle:5,1');

// Midtrans Webhook Callback
Route::post('/midtrans/callback', [PaymentCallbackController::class, 'receive']);