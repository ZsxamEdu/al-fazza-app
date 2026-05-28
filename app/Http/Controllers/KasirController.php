<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Transaction;
use App\Services\CheckoutService;
use Illuminate\Support\Facades\Auth;

class KasirController extends Controller
{
    protected $checkoutService;

    public function __construct(CheckoutService $checkoutService)
    {
        $this->checkoutService = $checkoutService;
    }

    public function index()
    {
        $products = Product::where('stok', '>', 0)->get();
        return view('kasir.pos', compact('products'));
    }

    public function prosesPos(Request $request)
    {
        // Validasi dasar input form kasir
        $request->validate([
            'cart_data'      => 'required|string',
            'payment_method' => 'required|string|in:Cash,QRIS,Transfer',
            'amount_paid'    => 'required|numeric|min:0',
            'change_amount'  => 'required|numeric|min:0',
        ]);

        $cartData = json_decode($request->cart_data, true);

        // Cek apakah JSON valid dan tidak kosong
        if (!is_array($cartData) || count($cartData) === 0) {
            return redirect()->back()->with('error', 'Data keranjang tidak valid atau kosong. Silakan tambahkan produk terlebih dahulu.');
        }

        $customerData = [
            'user_id' => Auth::id(),
            'customer_name' => 'Pelanggan Toko',
        ];

        $result = $this->checkoutService->processCheckout(
            $cartData,
            $customerData,
            'kasir',
            $request->payment_method,
            $request->amount_paid,
            $request->change_amount, // The service doesn't fully validate this right now, we can pass it
            'success'
        );

        if (!$result['success']) {
            return redirect()->back()->with('error', $result['message']);
        }

        return redirect()->route('kasir.selesai', $result['transaction']->id)
                        ->with('success', 'Transaksi berhasil disimpan!'); 
    }

    public function selesai($id)
    {
        $transaksi = Transaction::with('details.product')->findOrFail($id);
        return view('kasir.selesai', compact('transaksi'));
    }

    public function cetakStruk($id)
    {
        $transaksi = Transaction::with('details.product')->findOrFail($id);
        
        // Menghitung tinggi kertas secara dinamis berdasarkan jumlah produk
        $baseHeight = 270; // Tinggi dasar untuk header, info kasir, total, dan footer
        $itemHeight = 35; // Tinggi perkiraan untuk setiap baris produk
        $totalItems = $transaksi->details->count();
        
        $dynamicHeight = $baseHeight + ($totalItems * $itemHeight);
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('kasir.struk_pdf', compact('transaksi'));
        $pdf->setPaper([0, 0, 226, $dynamicHeight], 'portrait'); // Custom width 80mm, dynamic height
        
        return $pdf->stream('Struk_Pembayaran_' . $transaksi->invoice_number . '.pdf');
    }
}
