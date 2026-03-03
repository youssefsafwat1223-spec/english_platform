<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PromotionalEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $emailSubject;
    public $emailBody;
    public $ctaText;
    public $ctaUrl;
    public $recipientName;

    public function __construct(string $subject, string $body, string $recipientName, ?string $ctaText = null, ?string $ctaUrl = null)
    {
        $this->emailSubject = $subject;
        $this->emailBody = $body;
        $this->recipientName = $recipientName;
        $this->ctaText = $ctaText;
        $this->ctaUrl = $ctaUrl;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->emailSubject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.promotional',
        );
    }
}
