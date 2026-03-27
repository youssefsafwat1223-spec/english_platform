<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class UserNote extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'lesson_id',
        'note_text',
    ];

    // ==================== RELATIONSHIPS ====================

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    // ==================== ACCESSORS ====================

    /**
     * Get formatted created date
     */
    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('M d, Y');
    }

    /**
     * Get excerpt
     */
    public function getExcerptAttribute()
    {
        return Str::limit($this->note_text, 100);
    }

    public function getContentAttribute()
    {
        return $this->note_text ?? '';
    }

    public function getTitleAttribute()
    {
        $firstLine = Str::of($this->note_text ?? '')
            ->replace("\r", '')
            ->explode("\n")
            ->first();

        $firstLine = trim((string) $firstLine);

        return $firstLine !== ''
            ? Str::limit($firstLine, 60)
            : 'Untitled Note';
    }
}
