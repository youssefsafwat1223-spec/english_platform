<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TelegramBotSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'setting_key',
        'setting_value',
        'setting_type',
        'description',
        'updated_by',
    ];

    // ==================== RELATIONSHIPS ====================

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
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
            'json' => json_decode($this->setting_value, true),
            default => $this->setting_value,
        };
    }

    /**
     * Set setting value
     */
    public static function set($key, $value, $type = 'string', $description = null)
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
                'description' => $description,
                'updated_by' => auth()->id(),
            ]
        );
    }

    /**
     * Get setting value by key
     */
    public static function get($key, $default = null)
    {
        $setting = static::where('setting_key', $key)->first();

        if (!$setting) {
            return $default;
        }

        return $setting->getValue();
    }
}