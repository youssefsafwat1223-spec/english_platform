<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'setting_key',
        'setting_value',
        'setting_type',
        'setting_group',
        'is_public',
        'description',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'is_public' => 'boolean',
        ];
    }

    // ==================== RELATIONSHIPS ====================

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // ==================== SCOPES ====================

    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    public function scopeByGroup($query, $group)
    {
        return $query->where('setting_group', $group);
    }

    // ==================== METHODS ====================

    /**
     * Get setting value with proper type casting
     */
    public function getValue()
    {
        return match($this->setting_type) {
            'boolean' => filter_var($this->setting_value, FILTER_VALIDATE_BOOLEAN),
            'integer' => (int) $this->setting_value,
            'decimal' => (float) $this->setting_value,
            'json' => json_decode($this->setting_value, true),
            default => $this->setting_value,
        };
    }

    /**
     * Set system setting
     */
    public static function set($key, $value, $type = 'string', $group = 'general', $isPublic = false)
    {
        if ($type === 'json') {
            $value = json_encode($value);
        } elseif ($type === 'boolean') {
            $value = $value ? '1' : '0';
        }

        return static::updateOrCreate(
            ['setting_key' => $key],
            [
                'setting_value' => $value,
                'setting_type' => $type,
                'setting_group' => $group,
                'is_public' => $isPublic,
                'updated_by' => auth()->id(),
            ]
        );
    }

    /**
     * Get system setting
     */
    public static function get($key, $default = null)
    {
        $setting = static::where('setting_key', $key)->first();

        if (!$setting) {
            return $default;
        }

        return $setting->getValue();
    }

    /**
     * Get all settings by group
     */
    public static function getByGroup($group)
    {
        return static::where('setting_group', $group)
            ->get()
            ->mapWithKeys(fn($setting) => [$setting->setting_key => $setting->getValue()]);
    }
}