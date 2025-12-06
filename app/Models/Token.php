<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Token extends Model
{
    use HasFactory;

    protected $table = 'api_tokens';

    protected $fillable = [
        'name',
        'token',
        'max_usage',
        'usage_count',
        'expires_at',
        'is_active',
        'description',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
        'max_usage' => 'integer',
        'usage_count' => 'integer',
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->where(function ($q) {
                $q->whereNull('max_usage')
                    ->orWhereRaw('usage_count < max_usage');
            });
    }

    // Helpers
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

    public function getRemainingUsesAttribute()
    {
        if (!$this->max_usage) {
            return 'Unlimited';
        }

        return max(0, $this->max_usage - $this->usage_count);
    }

    public function getStatusAttribute()
    {
        if (!$this->is_active) {
            return 'inactive';
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return 'expired';
        }

        if ($this->max_usage && $this->usage_count >= $this->max_usage) {
            return 'depleted';
        }

        return 'active';
    }

    // Static methods
    public static function generateToken()
    {
        return Str::random(32);
    }
}
