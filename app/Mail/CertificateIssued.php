<?php

namespace App\Mail;

use App\Models\Certificate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class CertificateIssued extends Mailable
{
    use Queueable, SerializesModels;

    public $certificate;

    public function __construct(Certificate $certificate)
    {
        $this->certificate = $certificate;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Congratulations! Your Certificate is Ready',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.certificate-issued',
        );
    }

    public function attachments(): array
    {
        if ($this->certificate->pdf_path && Storage::exists($this->certificate->pdf_path)) {
            return [
                Storage::path($this->certificate->pdf_path),
            ];
        }

        return [];
    }
}
