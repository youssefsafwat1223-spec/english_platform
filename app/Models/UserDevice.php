<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDevice extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'device_token_hash',
        'device_label',
        'device_type',
        'platform',
        'browser',
        'user_agent',
        'ip_address',
        'approved_at',
        'last_seen_at',
        'last_login_at',
        'revoked_at',
    ];

    protected function casts(): array
    {
        return [
            'approved_at' => 'datetime',
            'last_seen_at' => 'datetime',
            'last_login_at' => 'datetime',
            'revoked_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function replacementRequests()
    {
        return $this->hasMany(DeviceReplacementRequest::class, 'replacement_for_device_id');
    }

    public function scopeActive($query)
    {
        return $query->whereNull('revoked_at');
    }

    public function getIsActiveAttribute(): bool
    {
        return is_null($this->revoked_at);
    }
}
