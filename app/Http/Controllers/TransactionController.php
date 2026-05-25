<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Product;
use App\Models\InventoryLog;
use App\Mail\InvoiceMail;
use Midtrans\Config;
use Midtrans\Snap;

class TransactionController extends Controller
{
    public function __construct()
    {
        // Set konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');

        Config::$curlOptions = [
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTPHEADER => [] // <-- Tambahan penyembuh error 10023
        ];
    }

    public function processCheckout(Request $request)
    {
        try {
            $invoice = 'ALF-' . date('YmdHis');
            
            // 1. Simpan Transaksi Utama (Ubah default Cash jadi Online)
            $transaction = Transaction::create([
                'invoice_number' => $invoice,
                'user_id' => Auth::id() ?? null, 
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'total_amount' => $request->total_price,
                'payment_status' => 'pending',
                'order_type' => 'online',
                'payment_method' => 'Midtrans (Pending)', // Set default sementara
                'amount_paid' => 0,
            ]);

            // 2. SIMPAN RINCIAN PESANAN (Biar rotinya tidak hilang)
            if ($request->items) {
                foreach ($request->items as $item) {
                    // Cari ID roti berdasarkan namanya (karena dari JS cuma ngirim nama)
                    $product = Product::where('nama', $item['name'])->first();
                    
                    if ($product) {
                        TransactionDetail::create([
                            'transaction_id' => $transaction->id,
                            'product_id' => $product->id,
                            'qty' => $item['quantity'],
                            'price' => $item['price'],
                            'subtotal' => $item['price'] * $item['quantity'],
                        ]);
                    }
                }
            }

            // 3. Siapkan data untuk dikirim ke Midtrans
            $params = [
                'transaction_details' => [
                    'order_id' => $invoice,
                    'gross_amount' => (int) $request->total_price,
                ],
                'customer_details' => [
                    'first_name' => $request->customer_name,
                    'phone' => $request->customer_phone,
                ],
            ];

            // 4. Minta Snap Token dari Midtrans
            $snapToken = Snap::getSnapToken($params);
            
            // SIMPAN TOKEN KE DATABASE
            $transaction->update([
                'snap_token' => $snapToken
            ]);

            // // === TAMBAHAN BARU: KIRIM EMAIL KE PEMBELI ===
            // if ($request->customer_email) {
            //     // Buat link yang akan diklik pembeli di email
            //     $linkInvoice = url('/checkout/invoice/' . $invoice);
            //     try {
            //         Mail::to($request->customer_email)->send(new InvoiceMail($transaction, $linkInvoice));
            //     } catch (\Exception $e) {
            //         \Log::error("Gagal mengirim email invoice: " . $e->getMessage());
            //     }
            // }
            
            return response()->json([
                'snap_token' => $snapToken,
                'invoice' => $invoice
            ]);
            
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function processCustomCheckout(Request $request)
    {
        try {
            // 1. Buat Nomor Invoice Khusus Custom Cake
            $invoice = 'ALF-CUST-' . date('YmdHis');

            // 2. SIMPAN TRANSAKSI (PAKAI JALUR VVIP BIAR ANTI ERROR)
            $transaction = new Transaction();
            $transaction->invoice_number = $invoice;
            $transaction->user_id = \Illuminate\Support\Facades\Auth::id() ?? null;
            $transaction->customer_name = $request->customer_name;
            $transaction->customer_email = $request->customer_email;
            $transaction->order_type = 'online'; 
            $transaction->total_amount = $request->total_price;
            $transaction->payment_status = 'pending';
            $transaction->payment_method = 'Midtrans';
            $transaction->amount_paid = 0;
            $transaction->save(); // Simpan paksa ke database

            // 3. Siapkan Data untuk Midtrans
            $params = [
                'transaction_details' => [
                    'order_id' => $invoice,
                    'gross_amount' => $request->total_price,
                ],
                'customer_details' => [
                    'first_name' => $request->customer_name,
                    'email' => $request->customer_email,
                    'phone' => $request->customer_phone,
                ],
            ];

            // 4. Minta Token Midtrans & Simpan
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            
            $transaction->update([
                'snap_token' => $snapToken
            ]);

            // // 5. Kirim Email Tagihan Pending 
            // if ($request->customer_email) {
            //     $linkInvoice = config('app.url') . '/checkout/invoice/' . $invoice;
            //     try {
            //         \Illuminate\Support\Facades\Mail::to($request->customer_email)->send(new \App\Mail\InvoiceMail($transaction, $linkInvoice));
            //     } catch (\Exception $e) {
            //         \Log::error("Gagal mengirim email invoice Custom: " . $e->getMessage());
            //     }
            // }
            
            return response()->json([
                'snap_token' => $snapToken,
                'invoice' => $invoice
            ]);
            
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function checkoutInvoice($invoice)
    {
        $transaksi = Transaction::where('invoice_number', $invoice)->firstOrFail();
        
        $snapToken = null; // Set default kosong

        if ($transaksi->payment_status == 'success') {
            $ui = [
                'color' => '#2e7d32',        // Hijau
                'bg_color' => '#e8f5e9',     // Hijau Muda
                'icon' => 'fa-circle-check',
                'title' => 'Pembayaran Berhasil!',
                'message' => 'Terima kasih telah berbelanja di Al-Fazza Bakery. Pesanan Anda sedang kami proses.',
                'badge' => 'LUNAS'
            ];
        } else {
            $ui = [
                'color' => '#ef6c00',        // Oranye
                'bg_color' => '#fff3e0',     // Oranye Muda
                'icon' => 'fa-clock',
                'title' => 'Menunggu Pembayaran',
                'message' => 'Silakan selesaikan pembayaran Anda sebelum batas waktu habis.',
                'badge' => 'PENDING'
            ];

            // SIHIR REGENERASI TOKEN: Jika pending, minta token baru ke Midtrans secara live
            try {
                $params = [
                    'transaction_details' => [
                        'order_id' => $transaksi->invoice_number,
                        'gross_amount' => (int) $transaksi->total_amount,
                    ],
                    'customer_details' => [
                        'first_name' => $transaksi->customer_name,
                    ],
                ];
                $snapToken = \Midtrans\Snap::getSnapToken($params);
            } catch (\Exception $e) {
                \Log::error("Gagal regenerasi Snap Token di Invoice: " . $e->getMessage());
            }
        }
        
        return view('checkout-invoice', compact('transaksi', 'ui', 'snapToken'));
    }

    public function callback(Request $request)
    {
        $serverKey = config('midtrans.server_key');
        
        $grossAmount = number_format($request->gross_amount, 0, '.', '');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);
        
        \Log::info("Midtrans Callback Masuk. Invoice: {$request->order_id}, Status: {$request->transaction_status}");

        if ($hashed == $request->signature_key) {
            
            // 1. Cari Transaksi berdasarkan Nomor Invoice
            $transaction = Transaction::where('invoice_number', $request->order_id)->first();
            
            if ($transaction) {
                
                // KONDISI A: LUNAS (Success / Settlement / Capture)
                if ($request->transaction_status == 'capture' || $request->transaction_status == 'settlement') {
                    
                    if ($transaction->payment_status == 'pending') {
                        $metodeBayar = strtoupper(str_replace('_', ' ', $request->payment_type));
                        
                        // Update transaksi jadi lunas
                        $transaction->update([
                            'payment_status' => 'success',
                            'payment_method' => $metodeBayar,
                            'amount_paid' => $request->gross_amount,
                        ]);

                        // Potong Stok
                        if ($transaction->details) {
                            foreach ($transaction->details as $detail) {
                                $product = Product::find($detail->product_id);
                                if ($product) {
                                    $product->decrement('stok', $detail->qty);
                                    InventoryLog::create([
                                        'product_id' => $product->id,
                                        'tipe' => 'keluar',
                                        'jumlah' => $detail->qty,
                                        'keterangan' => 'Terjual Online (' . $transaction->invoice_number . ') via ' . $metodeBayar,
                                    ]);
                                }
                            }
                        }

                        // EMAIL 1: KIRIM EMAIL LUNAS
                        if ($transaction->customer_email) {
                            $linkInvoice = config('app.url') . '/checkout/invoice/' . $transaction->invoice_number;
                            try {
                                \Illuminate\Support\Facades\Mail::to($transaction->customer_email)->send(new \App\Mail\InvoicePaidMail($transaction, $linkInvoice));
                            } catch (\Exception $e) {
                                \Log::error("Gagal mengirim email lunas: " . $e->getMessage());
                            }
                        }
                        \Log::info("Invoice {$request->order_id} BERHASIL Lunas.");
                    }
                } 
                
                // KONDISI B: PENDING (Pembeli baru memilih metode bayar, misal milih BCA VA)
                else if ($request->transaction_status == 'pending') {
                    
                    $metodeBayar = strtoupper(str_replace('_', ' ', $request->payment_type));
                    
                    // Update nama metode bayarnya di database biar admin tahu
                    $transaction->update([
                        'payment_method' => $metodeBayar . ' (Pending)'
                    ]);

                    // EMAIL 2: KIRIM EMAIL TAGIHAN PENDING
                    if ($transaction->customer_email) {
                        $linkInvoice = config('app.url') . '/checkout/invoice/' . $transaction->invoice_number;
                        try {
                            \Illuminate\Support\Facades\Mail::to($transaction->customer_email)->send(new \App\Mail\InvoiceMail($transaction, $linkInvoice));
                        } catch (\Exception $e) {
                            \Log::error("Gagal mengirim email pending: " . $e->getMessage());
                        }
                    }
                    \Log::info("Invoice {$request->order_id} Pending. Email Tagihan Dikirim.");
                }

            }
        } else {
            \Log::error("Verifikasi Signature Midtrans GAGAL untuk Invoice: {$request->order_id}");
        }
        
        return response()->json(['message' => 'Callback diproses']);
    }
}