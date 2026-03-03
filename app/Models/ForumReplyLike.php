<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForumReplyLike extends Model
{
    use HasFactory;

    protected $fillable = [
        'reply_id',
        'user_id',
    ];

    public $timestamps = true;

    // ==================== RELATIONSHIPS ====================

    public function reply()
    {
        return $this->belongsTo(ForumReply::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}