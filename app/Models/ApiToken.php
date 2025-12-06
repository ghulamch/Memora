<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ApiToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'token',
        'expires_at',
        'is_active',
        'usage_count',
        'max_usage',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    protected $hidden = [
        'token',
    ];

    public static function generate($name, $expiresAt = null, $maxUsage = null)
    {
        return self::create([
            'name' => $name,
            'token' => Str::random(64),
            'expires_at' => $expiresAt,
            'max_usage' => $maxUsage,
        ]);
    }

    public function isValid()
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        if ($this->max_usage && $this->usage_count >= $this->max_usage) {
            return false;
        }

        return true;
    }

    public function incrementUsage()
    {
        $this->increment('usage_count');
    }
}
