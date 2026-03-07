<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InactivityReminder extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $daysSinceActive;
    public $enrolledCourses;

    public function __construct(User $user, int $daysSinceActive, $enrolledCourses = null)
    {
        $this->user = $user;
        $this->daysSinceActive = $daysSinceActive;
        $this->enrolledCourses = $enrolledCourses;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'اشتقنالك! 👋 الكورسات بتاعتك في انتظارك',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.inactivity-reminder',
        );
    }
}
