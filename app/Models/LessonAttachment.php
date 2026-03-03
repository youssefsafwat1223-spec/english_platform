<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class LessonAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'lesson_id',
        'title',
        'file_path',
        'file_type',
        'file_size',
        'order_index',
    ];

    // ==================== RELATIONSHIPS ====================

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    // ==================== ACCESSORS ====================

    /**
     * Get download URL
     */
    public function getDownloadUrlAttribute()
    {
        return Storage::url($this->file_path);
    }

    /**
     * Get formatted file size
     */
    public function getFormattedSizeAttribute()
    {
        if (!$this->file_size) {
            return null;
        }

        if ($this->file_size < 1024) {
            return $this->file_size . ' KB';
        }

        return round($this->file_size / 1024, 2) . ' MB';
    }

    /**
     * Get file icon based on type
     */
    public function getFileIconAttribute()
    {
        return match($this->file_type) {
            'pdf' => 'fa-file-pdf',
            'docx', 'doc' => 'fa-file-word',
            'xlsx', 'xls' => 'fa-file-excel',
            'pptx', 'ppt' => 'fa-file-powerpoint',
            'jpg', 'jpeg', 'png', 'gif' => 'fa-file-image',
            default => 'fa-file',
        };
    }
}