<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CheckoutService;

class PaymentCallbackController extends Controller
{
    protected $checkoutService;

    public function __construct(CheckoutService $checkoutService)
    {
        $this->checkoutService = $checkoutService;
    }

    public function receive(Request $request)
    {
        $serverKey = config('midtrans.server_key');
        
        // Midtrans selalu mengirimkan gross_amount dengan format .00 (misal "15000.00").
        // Laravel sering otomatis mengubahnya menjadi angka bulat (15000).
        // Kita harus mengembalikannya ke format .00 agar gembok/signature-nya cocok!
        $grossAmount = number_format((float) $request->gross_amount, 2, '.', '');
        
        $hashed = hash("sha512", $request->order_id . $request->status_code . $grossAmount . $serverKey);
        
        if ($hashed == $request->signature_key) {
            $this->checkoutService->handleMidtransCallback($request);
        } else {
            \Illuminate\Support\Facades\Log::error('Midtrans Signature Error', [
                'expected_hash' => $hashed,
                'received_signature' => $request->signature_key,
                'gross_amount_used' => $grossAmount
            ]);
        }

        return response()->json(['message' => 'Callback processed']);
    }
}
