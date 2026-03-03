<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'enrollment_id',
        'certificate_id',
        'certificate_type',
        'final_score',
        'pdf_path',
        'qr_code_path',
        'verification_url',
        'issued_at',
        'downloaded_at',
        'download_count',
        'view_count',
        'linkedin_shared',
        'linkedin_shared_at',
    ];

    protected function casts(): array
    {
        return [
            'issued_at' => 'datetime',
            'downloaded_at' => 'datetime',
            'linkedin_shared' => 'boolean',
            'linkedin_shared_at' => 'datetime',
        ];
    }

    // ==================== RELATIONSHIPS ====================

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class);
    }

    // ==================== ACCESSORS ====================

    /**
     * Get PDF download URL
     */
    public function getPdfUrlAttribute()
    {
        if (!$this->pdf_path) {
            return null;
        }

        $diskName = config('filesystems.default', 'local');
        $disk = Storage::disk($diskName);

        if (! $disk->exists($this->pdf_path)) {
            return null;
        }

        if (method_exists($disk, 'providesTemporaryUrls') && $disk->providesTemporaryUrls()) {
            return $disk->temporaryUrl($this->pdf_path, now()->addMinutes(30));
        }

        return $disk->url($this->pdf_path);
    }

    /**
     * Get QR code URL
     */
    public function getQrCodeUrlAttribute()
    {
        if (!$this->qr_code_path) {
            return null;
        }

        $diskName = config('filesystems.default', 'local');
        $disk = Storage::disk($diskName);

        if (! $disk->exists($this->qr_code_path)) {
            return null;
        }

        if (method_exists($disk, 'providesTemporaryUrls') && $disk->providesTemporaryUrls()) {
            return $disk->temporaryUrl($this->qr_code_path, now()->addMinutes(30));
        }

        return $disk->url($this->qr_code_path);
    }

    /**
     * Get verification URL
     */
    public function getVerificationUrlAttribute()
    {
        return route('certificates.verify', $this->certificate_id);
    }

    /**
     * Get grade based on score
     */
    public function getGradeAttribute()
    {
        if ($this->final_score >= 90) return 'A';
        if ($this->final_score >= 80) return 'B';
        if ($this->final_score >= 70) return 'C';
        if ($this->final_score >= 60) return 'D';
        return 'F';
    }

    /**
     * Get performance level
     */
    public function getPerformanceLevelAttribute()
    {
        if ($this->final_score >= 90) return 'Excellent';
        if ($this->final_score >= 80) return 'Very Good';
        if ($this->final_score >= 70) return 'Good';
        return 'Satisfactory';
    }

    // ==================== METHODS ====================

    /**
     * Increment download count
     */
    public function incrementDownloads()
    {
        $this->increment('download_count');

        if (is_null($this->downloaded_at)) {
            $this->update(['downloaded_at' => now()]);
        }
    }

    /**
     * Increment view count
     */
    public function incrementViews()
    {
        $this->increment('view_count');
    }

    /**
     * Mark as shared on LinkedIn
     */
    public function markAsSharedOnLinkedIn()
    {
        $this->update([
            'linkedin_shared' => true,
            'linkedin_shared_at' => now(),
        ]);
    }

    /**
     * Generate certificate ID
     */
    public static function generateCertificateId($courseId, $prefix = null)
    {
        $prefix = $prefix ?: 'CERT';
        $prefix = strtoupper(preg_replace('/[^A-Z0-9]/', '', $prefix));

        if ($prefix === '') {
            $prefix = 'CERT';
        }

        $year = now()->year;
        
        // Get the highest existing number for this year to avoid duplicates
        $lastCert = Certificate::where('certificate_id', 'like', "{$prefix}-{$year}-%")
            ->orderByRaw("CAST(SUBSTRING_INDEX(certificate_id, '-', -1) AS UNSIGNED) DESC")
            ->first();

        if ($lastCert) {
            $lastNumber = (int) substr($lastCert->certificate_id, strrpos($lastCert->certificate_id, '-') + 1);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        $number = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        return "{$prefix}-{$year}-{$number}";
    }
}
