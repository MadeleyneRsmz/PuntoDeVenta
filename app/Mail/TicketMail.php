<?php

namespace App\Mail;

use App\Models\Sale;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/** Ticket de compra que se envía por Gmail cada vez que se registra una venta. */
class TicketMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Sale $sale)
    {
        $this->sale->loadMissing('items', 'cashier');
    }

    public function build()
    {
        return $this->subject("Ticket de compra {$this->sale->receipt_number} · " . config('app.name'))
            ->view('correos.ticket');
    }
}
