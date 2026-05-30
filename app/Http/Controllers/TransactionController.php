<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Services\CheckoutService;
use App\Http\Requests\ProcessCheckoutRequest;
use App\Http\Requests\CustomCheckoutRequest;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    protected $checkoutService;

    public function __construct(CheckoutService $checkoutService)
    {
        $this->checkoutService = $checkoutService;
    }

    public function processCheckout(ProcessCheckoutRequest $request)
    {
        $data = $request->validated();
        
        $customerData = [
            'user_id' => Auth::id() ?? null,
            'customer_name' => $data['customer_name'],
            'customer_email' => $data['customer_email'],
            'customer_phone' => $data['customer_phone'],
            'delivery_date' => $data['delivery_date'],
            'delivery_address' => $data['delivery_address'],
            'notes' => $data['notes'] ?? null,
        ];

        $result = $this->checkoutService->processCheckout(
            $data['items'],
            $customerData,
            'online',
            'Midtrans (Pending)',
            0,
            0,
            'pending'
        );

        if (!$result['success']) {
            return response()->json(['error' => $result['message']], 500);
        }

        return response()->json([
            'snap_token' => $result['snap_token'],
            'invoice' => $result['invoice'],
            'token' => $result['token']
        ]);
    }

    public function checkoutInvoice(Request $request, $invoice)
    {
        $transaksi = Transaction::with(['details.product' => function ($q) {
            $q->withTrashed();
        }])->where('invoice_number', $invoice)->firstOrFail();
        
        if ($transaksi->token !== $request->query('token')) {
            abort(403, 'Akses Ditolak: Token keamanan tidak valid atau tidak ditemukan.');
        }
        
        $ui = [];
        if ($transaksi->payment_status == 'success') {
            $ui = [
                'color' => '#388e3c',
                'bg_color' => '#e8f5e9',
                'icon' => 'fa-check',
                'title' => 'Pembayaran Berhasil!',
                'message' => 'Terima kasih, pesanan Anda sedang kami proses.',
                'badge' => 'LUNAS'
            ];
        } else if ($transaksi->payment_status == 'pending') {
            $ui = [
                'color' => '#ef6c00',
                'bg_color' => '#fff3e0',
                'icon' => 'fa-clock',
                'title' => 'Menunggu Pembayaran',
                'message' => 'Selesaikan pembayaran sebelum batas waktu habis.',
                'badge' => 'PENDING'
            ];
        } else {
            $ui = [
                'color' => '#d32f2f',
                'bg_color' => '#ffebee',
                'icon' => 'fa-xmark',
                'title' => 'Pembayaran Gagal/Kedaluwarsa',
                'message' => 'Mohon maaf, transaksi Anda telah dibatalkan.',
                'badge' => 'GAGAL'
            ];
        }

        return view('checkout-invoice', compact('transaksi', 'ui'));
    }

    public function processCustomCheckout(CustomCheckoutRequest $request)
    {
        $data = $request->validated();
        
        $invoice = 'CST-' . date('YmdHis');

        // Harga dihitung server-side berdasarkan ukuran kue yang tervalidasi.
        // Ini mencegah manipulasi harga dari sisi client.
        $pricemap = [
            '16 cm' => 150000,
            '18 cm' => 180000,
            '20 cm' => 220000,
            '22 cm' => 260000,
            '24 cm' => 300000,
            '30 cm' => 450000,
        ];
        $totalAmount = $pricemap[$data['ukuran']] ?? 150000;
        
        $data['user_id'] = Auth::id() ?? null;

        $result = $this->checkoutService->processCustomOrder($data, $invoice, $totalAmount);

        if (!$result['success']) {
            return response()->json(['error' => $result['message']], 500);
        }

        return response()->json([
            'success' => true,
            'snap_token' => $result['snap_token'],
            'invoice' => $result['invoice'],
            'token' => $result['token']
        ]);
    }
}