<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\InventoryLog;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\Auth;

class KasirController extends Controller
{
    public function index()
    {
        // Hanya tampilkan produk yang stoknya lebih dari 0
        $products = Product::where('stok', '>', 0)->get();
        return view('kasir.pos', compact('products'));
    }

    // Fungsi memproses data dari mesin Kasir
    public function prosesPos(Request $request)
    {
        // 1. Ubah teks JSON dari JavaScript kembali menjadi Array PHP
        $cartData = json_decode($request->cart_data, true);
        
        if (!$cartData || count($cartData) == 0) {
            return redirect()->back()->with('error', 'Keranjang kosong!');
        }

        // 2. Hitung Grand Total
        $grandTotal = 0;
        foreach ($cartData as $item) {
            $grandTotal += ($item['harga'] * $item['qty']);
        }

        // 3. Simpan ke tabel Transaksi Utama (Transactions)
        $invoiceNumber = 'POS-' . date('Ymd-His');
        
        $transaksi = Transaction::create([
            'invoice_number' => $invoiceNumber,
            'user_id' => Auth::id(),
            'customer_name' => 'Pelanggan Toko',
            'order_type' => 'kasir',
            'total_amount' => $grandTotal,
            'payment_status' => 'success',
            'payment_method' => $request->payment_method,
            'amount_paid' => $request->amount_paid,
            'change_amount' => $request->change_amount,
        ]);


        // 4. Simpan ke Detail Transaksi, Potong Stok, dan Catat Riwayat
        foreach ($cartData as $item) {
            TransactionDetail::create([
                'transaction_id' => $transaksi->id,
                'product_id'     => $item['id'],
                'qty'            => $item['qty'],
                'price'          => $item['harga'],
                'subtotal'       => $item['harga'] * $item['qty'],
            ]);

            // Potong Stok di Tabel Product
            $product = Product::find($item['id']);
            if ($product) {
                $product->decrement('stok', $item['qty']);
            }

            // Catat ke Buku Riwayat (Inventory Logs)
            InventoryLog::create([
                'product_id' => $item['id'],
                'tipe' => 'keluar',
                'jumlah' => $item['qty'],
                'keterangan' => 'Terjual via Kasir (Invoice: ' . $invoiceNumber . ')',
            ]);
        }

        // 5. Arahkan ke Halaman Sukses / Cetak Struk (Nanti kita buat halamannya)
        return redirect()->route('kasir.selesai', $transaksi->id)
                        ->with('success', 'Transaksi berhasil disimpan!'); 
        
        
        
    }
      // Fungsi untuk menampilkan halaman ringkasan & tombol cetak
    public function selesai($id)
    {
        // Ambil data transaksi beserta detail produknya
        $transaksi = Transaction::with('details.product')->findOrFail($id);
        return view('kasir.selesai', compact('transaksi'));
    }



}
