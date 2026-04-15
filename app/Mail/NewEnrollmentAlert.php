<?php

namespace App\Mail;

use App\Models\Enrollment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewEnrollmentAlert extends Mailable
{
    use Queueable, SerializesModels;

    public Enrollment $enrollment;

    public function __construct(Enrollment $enrollment)
    {
        $this->enrollment = $enrollment->load(['user', 'course']);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🎉 اشتراك جديد — ' . $this->enrollment->course->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.new-enrollment-alert',
        );
    }
}
