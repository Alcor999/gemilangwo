<?php

namespace App\Mail;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Bank;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentInstructionMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public Order $order,
        public Payment $payment,
        public Bank $bank
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Instruksi Pembayaran - ' . $this->order->order_number,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.payment-instruction',
            with: [
                'order' => $this->order,
                'payment' => $this->payment,
                'bank' => $this->bank,
            ]
        );
    }
}
