<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Order $order, public string $previousStatus)
    {
    }

    public function envelope(): Envelope
    {
        $statusLabel = match($this->order->status) {
            'pending' => 'Pending',
            'confirmed' => 'Confirmed',
            'in_progress' => 'In Progress',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
            default => ucfirst($this->order->status),
        };

        return new Envelope(
            subject: 'Order Status Updated - Order #' . $this->order->id . ' is ' . $statusLabel,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.order-status',
            with: [
                'order' => $this->order,
                'customer' => $this->order->user,
                'package' => $this->order->package,
                'previousStatus' => $this->previousStatus,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
