<?php

namespace App\Models;

use App\Models\Concerns\RepairsMojibakeAttributes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Course extends Model
{
    use HasFactory, SoftDeletes, RepairsMojibakeAttributes;

    protected array $repairableTextAttributes = [
        'title',
        'short_description',
        'description',
    ];

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    protected $fillable = [
        'title',
        'slug',
        'short_description',
        'description',
        'price',
        'thumbnail',
        'intro_video_url',
        'estimated_duration_weeks',
        'is_active',
        'order_index',
        'total_students',
        'average_rating',
        'total_reviews',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'average_rating' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Boot method - Auto-generate slug
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($course) {
            if (empty($course->slug)) {
                $course->slug = Str::slug($course->title);
            }
        });
    }

    // ==================== RELATIONSHIPS ====================

    /**
     * Lessons in this course
     */
    public function lessons()
    {
        return $this->hasMany(Lesson::class)->orderBy('order_index');
    }

    /**
     * Levels in this course
     */
    public function levels()
    {
        return $this->hasMany(CourseLevel::class)->orderBy('order_index');
    }

    /**
     * Questions for this course
     */
    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    /**
     * Quizzes for this course
     */
    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }

    /**
     * Enrollments in this course
     */
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    /**
     * Students enrolled in this course
     */
    public function students()
    {
        return $this->belongsToMany(User::class, 'enrollments')
            ->withTimestamps()
            ->withPivot(['progress_percentage', 'completed_at', 'certificate_id']);
    }

    /**
     * Payments for this course
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Certificates issued for this course
     */
    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    /**
     * User who created this course
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // ==================== SCOPES ====================

    /**
     * Active courses only
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Order by popularity (total students)
     */
    public function scopePopular($query)
    {
        return $query->orderBy('total_students', 'desc');
    }

    /**
     * Order by rating
     */
    public function scopeTopRated($query)
    {
        return $query->orderBy('average_rating', 'desc');
    }

    // ==================== ACCESSORS ====================

    /**
     * Get course URL
     */
    public function getUrlAttribute()
    {
        return route('courses.show', $this->slug);
    }

    /**
     * Get formatted price
     */
    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 2) . ' ر.س';
    }

    /**
     * Get total lessons count
     */
    public function getTotalLessonsAttribute()
    {
        return $this->lessons()->count();
    }

    // ==================== METHODS ====================

    /**
     * Calculate discounted price
     */
    public function getDiscountedPrice($discountPercentage)
    {
        $discount = ($this->price * $discountPercentage) / 100;
        return $this->price - $discount;
    }

    /**
     * Increment student count
     */
    public function incrementStudents()
    {
        $this->increment('total_students');
    }

    public function decrementStudents(): void
    {
        if ($this->total_students > 0) {
            $this->decrement('total_students');
        }
    }

    /**
     * Update average rating
     */
    public function updateRating($newRating)
    {
        $currentTotal = $this->average_rating * $this->total_reviews;
        $this->total_reviews++;
        $this->average_rating = ($currentTotal + $newRating) / $this->total_reviews;
        $this->save();
    }
}
