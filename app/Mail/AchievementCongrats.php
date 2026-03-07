<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AchievementCongrats extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $achievementType; // 'course_completed', 'high_score', 'certificate_earned'
    public $achievementData; // ['course_title' => '...', 'score' => 95, etc.]

    public function __construct(User $user, string $achievementType, array $achievementData)
    {
        $this->user = $user;
        $this->achievementType = $achievementType;
        $this->achievementData = $achievementData;
    }

    public function envelope(): Envelope
    {
        $subjects = [
            'course_completed' => '🎉 مبروك! أكملت الكورس!',
            'high_score' => '🌟 ممتاز! حصلت على درجة عالية!',
            'certificate_earned' => '🎓 شهادتك جاهزة!',
        ];

        return new Envelope(
            subject: $subjects[$this->achievementType] ?? '🏆 مبروك على إنجازك!',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.achievement',
        );
    }
}
