<?php

namespace App\Mail;

use App\Models\Review;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReviewSubmissionMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Review $review)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Review Submitted - Thank You!',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.review-submission',
            with: [
                'review' => $this->review,
                'customer' => $this->review->user,
                'package' => $this->review->package,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
