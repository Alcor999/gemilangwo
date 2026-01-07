<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $type,
        public array $data = []
    ) {
    }

    public function envelope(): Envelope
    {
        $subject = match($this->type) {
            'new_order' => '🎉 New Order Received - #' . ($this->data['order_id'] ?? 'N/A'),
            'new_review' => '⭐ New Review Received',
            'payment_received' => '💰 Payment Received - #' . ($this->data['order_id'] ?? 'N/A'),
            default => 'New Notification from Gemilang WO',
        };

        return new Envelope(
            subject: $subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin-notification',
            with: [
                'type' => $this->type,
                'data' => $this->data,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
