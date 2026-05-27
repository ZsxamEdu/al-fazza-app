<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Product;
use App\Models\Review;

class ReviewController extends Controller
{
    public function create($invoice, $product_id)
    {
        $transaction = Transaction::where('invoice_number', $invoice)->firstOrFail();
        $product = Product::findOrFail($product_id);

        // Hanya pesanan yang sudah selesai yang bisa di-review
        if ($transaction->order_status !== 'selesai') {
            return redirect()->route('checkout.invoice', $invoice)->with('error', 'Pesanan belum selesai, tidak dapat memberikan ulasan.');
        }

        // Cek apakah produk ini ada di transaksi tersebut
        $detailExists = TransactionDetail::where('transaction_id', $transaction->id)
            ->where('product_id', $product->id)
            ->exists();

        if (!$detailExists) {
            return redirect()->route('checkout.invoice', $invoice)->with('error', 'Produk tidak ditemukan pada transaksi ini.');
        }

        // Cek apakah ulasan sudah pernah diberikan
        $reviewExists = Review::where('transaction_id', $transaction->id)
            ->where('product_id', $product->id)
            ->exists();

        if ($reviewExists) {
            return redirect()->route('checkout.invoice', $invoice)->with('error', 'Anda sudah memberikan ulasan untuk produk ini.');
        }

        return view('review.create', compact('transaction', 'product'));
    }

    public function store(Request $request, $invoice, $product_id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $transaction = Transaction::where('invoice_number', $invoice)->firstOrFail();
        $product = Product::findOrFail($product_id);

        if ($transaction->order_status !== 'selesai') {
            return redirect()->route('checkout.invoice', $invoice)->with('error', 'Pesanan belum selesai, tidak dapat memberikan ulasan.');
        }

        $detailExists = TransactionDetail::where('transaction_id', $transaction->id)
            ->where('product_id', $product->id)
            ->exists();

        if (!$detailExists) {
            return redirect()->route('checkout.invoice', $invoice)->with('error', 'Produk tidak ditemukan pada transaksi ini.');
        }

        $reviewExists = Review::where('transaction_id', $transaction->id)
            ->where('product_id', $product->id)
            ->exists();

        if ($reviewExists) {
            return redirect()->route('checkout.invoice', $invoice)->with('error', 'Anda sudah memberikan ulasan untuk produk ini.');
        }

        // Simpan review
        Review::create([
            'transaction_id' => $transaction->id,
            'product_id' => $product->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        // Hitung ulang rata-rata dan perbarui produk (Metode Cache)
        $averageRating = Review::where('product_id', $product->id)->avg('rating');
        $product->update([
            'rating' => round($averageRating, 1) // simpan dalam 1 angka desimal
        ]);

        return redirect()->route('checkout.invoice', $invoice)->with('success', 'Terima kasih! Ulasan Anda telah disimpan.');
    }
}
