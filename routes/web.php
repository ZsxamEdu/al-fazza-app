<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

// 1. Rute Dinamis (Mengambil data roti dari Database menggunakan Controller)
Route::get('/', [ProductController::class, 'index']);
Route::get('/kategori', [ProductController::class, 'kategori']);
Route::get('/detail/{id}', [ProductController::class, 'detail']);

// 2. Rute Statis (Hanya menampilkan view dasar, belum butuh data dari database)
Route::get('/about', function () {
    return view('about');
});

Route::get('/checkout', function () {
    return view('checkout');
});

Route::get('/custom-order', function () {
    return view('custom-order');
});