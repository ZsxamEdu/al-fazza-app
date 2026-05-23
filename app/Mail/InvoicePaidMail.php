<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Transaction;

class InvoicePaidMail extends Mailable
{
    use Queueable, SerializesModels;

    public $transaction;
    public $linkInvoice;

    public function __construct(Transaction $transaction, $linkInvoice)
    {
        $this->transaction = $transaction;
        $this->linkInvoice = $linkInvoice;
    }

    public function build()
    {
        return $this->subject('Pembayaran Berhasil! - ' . $this->transaction->invoice_number)
                    ->view('emails.invoice-paid');
    }
}