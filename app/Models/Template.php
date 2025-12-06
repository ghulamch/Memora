<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Template extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'thumbnail',
        'background_image',
        'canvas_width',
        'canvas_height',
        'config',
        'is_active',
    ];

    protected $casts = [
        'config' => 'array',
        'is_active' => 'boolean',
    ];

    protected $appends = ['thumbnail_url', 'background_url'];

    public function slots()
    {
        return $this->hasMany(TemplateSlot::class)->orderBy('slot_order');
    }

    public function getThumbnailUrlAttribute()
    {
        return $this->thumbnail ? Storage::url($this->thumbnail) : null;
    }

    public function getBackgroundUrlAttribute()
    {
        return $this->background_image ? Storage::url($this->background_image) : null;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
