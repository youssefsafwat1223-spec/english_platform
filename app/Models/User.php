<?php

namespace App\Models;

use App\Notifications\ResetPasswordNotification;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'secondary_email',
        'telegram_username',
        'telegram_chat_id',
        'telegram_linked_at',
        'telegram_reminders',
        'role',
        'avatar',
        'is_active',
        'onboarding_completed',
        'total_points',
        'current_streak',
        'longest_streak',
        'last_activity_at',
        'referral_code',
        'referred_by',
        'referral_discount_used',
        'referral_discount_expires_at',
        'age',
        'google_id',
        'auth_type',
        'email_verified_at',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_confirmed_at',
        'streampay_consumer_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'telegram_linked_at' => 'datetime',
            'last_activity_at' => 'datetime',
            'referral_discount_expires_at' => 'datetime',
            'two_factor_confirmed_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'referral_discount_used' => 'boolean',
            'telegram_reminders' => 'boolean',
            'two_factor_secret' => 'encrypted',
            'two_factor_recovery_codes' => 'encrypted:array',
        ];
    }

    /**
     * Boot method - Generate referral code on user creation
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            if (empty($user->referral_code)) {
                $user->referral_code = strtoupper(Str::random(8));
            }
        });
    }

    // ==================== RELATIONSHIPS ====================

    /**
     * User who referred this user
     */
    public function referrer()
    {
        return $this->belongsTo(User::class, 'referred_by');
    }

    /**
     * Users referred by this user
     */
    public function referrals()
    {
        return $this->hasMany(Referral::class, 'referrer_id');
    }

    /**
     * Courses this user is enrolled in
     */
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    /**
     * Courses created by this user (if admin)
     */
    public function createdCourses()
    {
        return $this->hasMany(Course::class, 'created_by');
    }

    /**
     * Live sessions created by this user.
     */
    public function createdLiveSessions()
    {
        return $this->hasMany(LiveSession::class, 'created_by');
    }

    /**
     * Lesson progress records
     */
    public function lessonProgress()
    {
        return $this->hasMany(LessonProgress::class);
    }

    /**
     * Quiz attempts
     */
    public function quizAttempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }

    /**
     * Pronunciation attempts
     */
    public function pronunciationAttempts()
    {
        return $this->hasMany(PronunciationAttempt::class);
    }

    /**
     * Daily questions for this user
     */
    public function dailyQuestions()
    {
        return $this->hasMany(DailyQuestion::class);
    }

    /**
     * Devices registered for this user.
     */
    public function devices()
    {
        return $this->hasMany(UserDevice::class);
    }

    /**
     * Device replacement requests created by this user.
     */
    public function deviceReplacementRequests()
    {
        return $this->hasMany(DeviceReplacementRequest::class);
    }

    /**
     * Payments made by this user
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Certificates earned
     */
    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    /**
     * Forum topics created
     */
    public function forumTopics()
    {
        return $this->hasMany(ForumTopic::class);
    }

    /**
     * Forum replies
     */
    public function forumReplies()
    {
        return $this->hasMany(ForumReply::class);
    }

    /**
     * Lesson comments
     */
    public function lessonComments()
    {
        return $this->hasMany(LessonComment::class);
    }

    /**
     * User notes
     */
    public function notes()
    {
        return $this->hasMany(UserNote::class);
    }

    /**
     * Testimonial submitted by the student.
     */
    public function testimonial()
    {
        return $this->hasOne(Testimonial::class);
    }

    /**
     * Notifications
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Points history
     */
    public function pointsHistory()
    {
        return $this->hasMany(PointsHistory::class);
    }

    /**
     * Achievements earned
     */
    public function achievements()
    {
        return $this->belongsToMany(Achievement::class, 'user_achievements')
            ->withTimestamps()
            ->withPivot('earned_at');
    }

    // ==================== SCOPES ====================

    /**
     * Scope for active users only
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for students only
     */
    public function scopeStudents($query)
    {
        return $query->where('role', 'student');
    }

    /**
     * Scope for admins only
     */
    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    /**
     * Scope for users with telegram linked
     */
    public function scopeTelegramLinked($query)
    {
        return $query->whereNotNull('telegram_chat_id');
    }

    // ==================== ACCESSORS ====================

    /**
     * Get referral URL
     */
    public function getReferralUrlAttribute()
    {
        return url("/ref/{$this->referral_code}");
    }

    /**
     * Check if user is admin
     */
    public function getIsAdminAttribute()
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is student
     */
    public function getIsStudentAttribute()
    {
        return $this->role === 'student';
    }

    /**
     * Check if telegram is linked
     */
    public function getIsTelegramLinkedAttribute()
    {
        return !is_null($this->telegram_chat_id);
    }

    /**
     * Check if user has available referral discount
     */
    public function getHasReferralDiscountAttribute()
    {
        if ($this->referral_discount_used) {
            return false;
        }

        if (!$this->referral_discount_expires_at) {
            return false;
        }

        return now()->lessThan($this->referral_discount_expires_at);
    }

    /**
     * Check if two-factor authentication is enabled for this user.
     */
    public function hasTwoFactorEnabled(): bool
    {
        return $this->is_admin
            && filled($this->two_factor_secret)
            && !is_null($this->two_factor_confirmed_at);
    }

    // ==================== METHODS ====================

    /**
     * Add points to user
     */
    public function addPoints(int $points, string $activityType, $activityId = null, string $description = null)
    {
        $this->increment('total_points', $points);

        return PointsHistory::create([
            'user_id' => $this->id,
            'points_earned' => $points,
            'activity_type' => $activityType,
            'activity_id' => $activityId,
            'description' => $description,
        ]);
    }

    /**
     * Update activity timestamp
     */
    public function updateActivity()
    {
        $this->update(['last_activity_at' => now()]);
    }

    /**
     * Get user rank based on points
     */
    public function getRank()
    {
        return User::where('total_points', '>', $this->total_points)
            ->where('role', 'student')
            ->count() + 1;
    }

    /**
     * Check if user has completed a specific lesson
     */
    public function hasCompletedLesson($lessonId)
    {
        return $this->lessonProgress()
            ->where('lesson_id', $lessonId)
            ->where('is_completed', true)
            ->exists();
    }

    /**
     * Get enrollment for a specific course
     */
    public function getEnrollment($courseId)
    {
        return $this->enrollments()
            ->where('course_id', $courseId)
            ->first();
    }

    /**
     * Check if user is enrolled in a course
     */
    public function isEnrolledIn($courseId)
    {
        return $this->enrollments()
            ->where('course_id', $courseId)
            ->exists();
    }

    /**
     * Send the password reset notification.
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }
}
