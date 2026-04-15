<?php

namespace App\Mail;

use App\Models\InstallmentPlan;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InstallmentConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public InstallmentPlan $plan)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'تأكيد الاشتراك بالتقسيط — ' . $this->plan->course->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.installment-confirmation',
        );
    }
}
