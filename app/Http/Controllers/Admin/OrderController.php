<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Http\Requests\UpdateOrderStatusRequest;

class OrderController extends Controller
{
    public function pesananIndex(Request $request)
    {
        $query = Transaction::with(['details.product' => function ($q) {
            $q->withTrashed();
        }])
                            ->where('payment_status', 'success');

        if ($request->filled('status')) {
            $query->where('order_status', $request->status);
        }

        if ($request->filled('jenis')) {
            $query->where('order_type', $request->jenis);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%");
            });
        }

        $pesanan = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('admin.pesanan.index', compact('pesanan'));
    }

    public function pesananUpdateStatus(UpdateOrderStatusRequest $request, $id)
    {
        $transaction = Transaction::findOrFail($id);
        
        $transaction->update([
            'order_status' => $request->order_status
        ]);

        if ($transaction->customer_email) {
            try {
                \Illuminate\Support\Facades\Mail::to($transaction->customer_email)->send(new \App\Mail\OrderStatusUpdated($transaction));
            } catch (\Exception $e) {
                // Biarkan lanjut jika gagal kirim email (misal tidak ada koneksi) agar tidak memblokir update status
                \Illuminate\Support\Facades\Log::error('Gagal kirim email: ' . $e->getMessage());
            }
        }

        return redirect()->route('admin.pesanan.index')->with('success', 'Status pesanan diperbarui & email telah dikirim (jika email pelanggan tersedia)!');
    }
}
