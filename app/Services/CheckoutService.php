<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\InventoryLog;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Support\Facades\Mail;
use Exception;

class CheckoutService
{
    public function processCheckout($items, $customerData, $orderType, $paymentMethod, $amountPaid = 0, $changeAmount = 0, $paymentStatus = 'pending')
    {
        DB::beginTransaction();
        try {
            $invoice = ($orderType === 'kasir') ? 'POS-' . date('Ymd-His') : 'ALF-' . date('YmdHis');
            
            $serverGrandTotal = 0;
            $validItems = [];

            if (empty($items)) {
                throw new Exception('Keranjang kosong!');
            }

            foreach ($items as $item) {
                if (!isset($item['id'])) {
                    throw new Exception('Format keranjang sudah usang. Mohon kosongkan keranjang Anda dan tambahkan ulang produk.');
                }

                // Pessimistic Locking untuk mencegah race condition (2 orang beli barang sisa 1 di saat bersamaan)
                $product = Product::where('id', $item['id'])->lockForUpdate()->first();
                
                if (!$product) {
                    throw new Exception('Produk tidak ditemukan!');
                }

                $qty = isset($item['quantity']) ? $item['quantity'] : (isset($item['qty']) ? $item['qty'] : 1);

                if ($product->stok < $qty) {
                    throw new Exception('Stok ' . $product->nama . ' tidak mencukupi! Sisa stok: ' . $product->stok);
                }

                $subtotal = $product->harga * $qty;
                $serverGrandTotal += $subtotal;

                $validItems[] = [
                    'product_id' => $product->id,
                    'qty' => $qty,
                    'price' => $product->harga,
                    'subtotal' => $subtotal,
                    'nama_produk' => $product->nama
                ];
            }

            $token = \Illuminate\Support\Str::random(32);

            $transactionData = array_merge([
                'invoice_number' => $invoice,
                'total_amount' => $serverGrandTotal,
                'payment_status' => $paymentStatus,
                'order_type' => $orderType,
                'order_status' => ($orderType === 'kasir') ? 'selesai' : 'baru',
                'payment_method' => $paymentMethod,
                'amount_paid' => $amountPaid,
                'change_amount' => $changeAmount,
                'token' => $token,
            ], $customerData);

            $transaction = Transaction::create($transactionData);

            foreach ($validItems as $vItem) {
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $vItem['product_id'],
                    'qty' => $vItem['qty'],
                    'price' => $vItem['price'],
                    'subtotal' => $vItem['subtotal'],
                ]);

                // Untuk order kasir: langsung kurangi stok karena pembayaran tunai/langsung
                // Untuk order online: stok BELUM dikurangi di sini.
                // Stok akan dikurangi oleh handleMidtransCallback saat status menjadi 'success'.
                if ($orderType === 'kasir') {
                    $product = Product::find($vItem['product_id']);
                    $product->decrement('stok', $vItem['qty']);

                    InventoryLog::create([
                        'product_id' => $product->id,
                        'tipe' => 'keluar',
                        'jumlah' => $vItem['qty'],
                        'keterangan' => 'Terjual via Kasir (' . $invoice . ')',
                    ]);
                }
            }

            $snapToken = null;
            if ($orderType !== 'kasir') {
                
                $this->configureMidtrans();

                $params = [
                    'transaction_details' => [
                        'order_id' => $invoice,
                        'gross_amount' => (int) $serverGrandTotal,
                    ],
                    'customer_details' => [
                        'first_name' => $customerData['customer_name'] ?? 'Guest',
                        'phone' => $customerData['customer_phone'] ?? '',
                    ],
                ];

                $snapToken = Snap::getSnapToken($params);
                $transaction->update(['snap_token' => $snapToken]);

                // Kirim email pending
                if (!empty($customerData['customer_email'])) {
                    try {
                        $linkInvoice = config('app.url') . '/checkout/invoice/' . $transaction->invoice_number . '?token=' . $transaction->token;
                        Mail::send('emails.invoice', ['transaction' => $transaction, 'linkInvoice' => $linkInvoice], function ($message) use ($transaction) {
                            $message->to($transaction->customer_email, $transaction->customer_name)
                                    ->subject('Tagihan Pesanan ' . $transaction->invoice_number);
                        });
                    } catch (Exception $e) {
                        \Illuminate\Support\Facades\Log::error('Email pending failed: ' . $e->getMessage());
                    }
                }
            }

            DB::commit();

            return [
                'success' => true,
                'transaction' => $transaction,
                'snap_token' => $snapToken,
                'invoice' => $invoice,
                'token' => $token
            ];
            
        } catch (Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function processCustomOrder($data, $invoice, $totalAmount)
    {
        DB::beginTransaction();
        try {
            $token = \Illuminate\Support\Str::random(32);

            $transaction = Transaction::create([
                'invoice_number' => $invoice,
                'user_id' => $data['user_id'] ?? null,
                'customer_name' => $data['customer_name'],
                'customer_email' => $data['customer_email'],
                'customer_phone' => $data['customer_phone'],
                'delivery_date' => $data['delivery_date'],
                'delivery_address' => $data['delivery_address'],
                'custom_details' => $data['custom_details'],
                'notes' => ($data['notes'] ?? '-'),
                'order_type' => 'custom-order',
                'total_amount' => $totalAmount, 
                'payment_status' => 'pending',
                'payment_method' => 'Midtrans (Pending)',
                'token' => $token,
            ]);

            $this->configureMidtrans();

            $params = [
                'transaction_details' => [
                    'order_id' => $invoice,
                    'gross_amount' => (int) $totalAmount,
                ],
                'customer_details' => [
                    'first_name' => $data['customer_name'],
                    'email' => $data['customer_email'],
                    'phone' => $data['customer_phone'],
                ],
            ];

            $snapToken = Snap::getSnapToken($params);
            $transaction->update(['snap_token' => $snapToken]);

            // Kirim email pending
            if (!empty($data['customer_email'])) {
                try {
                    $linkInvoice = config('app.url') . '/checkout/invoice/' . $transaction->invoice_number . '?token=' . $transaction->token;
                    Mail::send('emails.invoice', ['transaction' => $transaction, 'linkInvoice' => $linkInvoice], function ($message) use ($transaction) {
                        $message->to($transaction->customer_email, $transaction->customer_name)
                                ->subject('Tagihan Pesanan ' . $transaction->invoice_number);
                    });
                } catch (Exception $e) {
                    \Illuminate\Support\Facades\Log::error('Email pending failed: ' . $e->getMessage());
                }
            }

            DB::commit();

            return [
                'success' => true,
                'snap_token' => $snapToken,
                'invoice' => $invoice,
                'token' => $token
            ];
        } catch (Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function handleMidtransCallback($notification)
    {
        DB::beginTransaction();
        try {
            $this->configureMidtrans();

            $notif = new \Midtrans\Notification();

            $transaction = $notif->transaction_status;
            $type = $notif->payment_type;
            $order_id = $notif->order_id;
            $fraud = $notif->fraud_status;

            $transaksi = Transaction::where('invoice_number', $order_id)->first();
            
            if (!$transaksi) {
                throw new Exception('Transaksi tidak ditemukan.');
            }

            if ($transaction == 'capture') {
                if ($type == 'credit_card') {
                    if ($fraud == 'challenge') {
                        $transaksi->update(['payment_status' => 'pending']);
                    } else {
                        $transaksi->update(['payment_status' => 'success', 'payment_method' => $type, 'amount_paid' => (int) $notif->gross_amount]);
                        // Kurangi stok setelah pembayaran kartu kredit dikonfirmasi sukses
                        if ($transaksi->order_type == 'online') {
                            $this->kurangiStokOnline($transaksi, $order_id);
                        }
                    }
                }
            } else if ($transaction == 'settlement') {
                $transaksi->update(['payment_status' => 'success', 'payment_method' => $type, 'amount_paid' => (int) $notif->gross_amount]);
                // Kurangi stok setelah pembayaran online dikonfirmasi settlement (sukses)
                if ($transaksi->order_type == 'online') {
                    $this->kurangiStokOnline($transaksi, $order_id);
                }
            } else if ($transaction == 'pending') {
                $transaksi->update(['payment_status' => 'pending']);
            } else if ($transaction == 'deny' || $transaction == 'expire' || $transaction == 'cancel') {
                $transaksi->update(['payment_status' => 'failed']);
                // Untuk order online: stok tidak perlu dikembalikan karena memang belum pernah dikurangi.
                // (Pengurangan stok hanya terjadi saat settlement/capture sukses di atas)
            }

            if ($transaksi->customer_email && in_array($transaksi->payment_status, ['success', 'failed'])) {
                try {
                    $linkInvoice = config('app.url') . '/checkout/invoice/' . $transaksi->invoice_number . '?token=' . $transaksi->token;
                    $emailTemplate = ($transaksi->payment_status == 'success') ? 'emails.invoice-paid' : 'emails.invoice';
                    
                    Mail::send($emailTemplate, ['transaction' => $transaksi, 'linkInvoice' => $linkInvoice], function ($message) use ($transaksi) {
                        $message->to($transaksi->customer_email, $transaksi->customer_name)
                                ->subject('Status Pembayaran Pesanan ' . $transaksi->invoice_number);
                    });
                } catch (Exception $e) {
                    \Illuminate\Support\Facades\Log::error('Email failed: ' . $e->getMessage());
                }
            }

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Gaib Error di Callback: ' . $e->getMessage() . ' di baris ' . $e->getLine());
            return false;
        }
    }

    /**
     * Konfigurasi Midtrans SDK — dipanggil sebelum setiap operasi Midtrans.
     * SSL bypass hanya diaktifkan di environment local (localhost/XAMPP).
     */
    private function configureMidtrans()
    {
        Config::$serverKey    = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production', false);
        Config::$isSanitized  = true;
        Config::$is3ds        = true;

        // Bypass verifikasi SSL hanya di environment local.
        // Di production, SSL harus tetap aktif untuk keamanan transaksi.
        if (app()->environment('local')) {
            Config::$curlOptions = [
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_HTTPHEADER     => [], // Fix bug SDK Midtrans (Undefined array key 10023)
            ];
        }
    }

    /**
     * Kurangi stok produk setelah pembayaran online dikonfirmasi sukses oleh Midtrans.
     * Dipanggil dari handleMidtransCallback saat status settlement atau capture+success.
     */
    private function kurangiStokOnline($transaksi, $order_id)
    {
        $details = TransactionDetail::where('transaction_id', $transaksi->id)->get();
        foreach ($details as $detail) {
            $product = Product::find($detail->product_id);
            if ($product) {
                $product->decrement('stok', $detail->qty);

                InventoryLog::create([
                    'product_id' => $product->id,
                    'tipe'       => 'keluar',
                    'jumlah'     => $detail->qty,
                    'keterangan' => 'Terjual Online (' . $order_id . ')',
                ]);
            }
        }
    }
}
