<?php

namespace App\Models;

use App\Models\Concerns\RepairsMojibakeAttributes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory, RepairsMojibakeAttributes;

    protected array $repairableTextAttributes = [
        'name',
        'role',
        'content',
    ];

    protected $fillable = [
        'name',
        'role',
        'content',
        'avatar',
        'rating',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'rating' => 'integer',
        'sort_order' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('created_at', 'desc');
    }
}
