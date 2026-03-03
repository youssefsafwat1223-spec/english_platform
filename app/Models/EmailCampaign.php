<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailCampaign extends Model
{
    protected $fillable = [
        'subject',
        'body',
        'cta_text',
        'cta_url',
        'target_audience',
        'target_course_id',
        'recipients_count',
        'sent_count',
        'status',
        'sent_at',
        'created_by',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function targetCourse()
    {
        return $this->belongsTo(Course::class, 'target_course_id');
    }

    public function getStatusBadgeAttribute()
    {
        return match ($this->status) {
            'draft' => 'warning',
            'sending' => 'info',
            'sent' => 'success',
            'failed' => 'danger',
            default => 'secondary',
        };
    }
}
