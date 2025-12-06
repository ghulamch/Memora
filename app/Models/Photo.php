<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Photo extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_path',
        'session_code',
        'original_filename',
        'file_size',
        'mime_type',
    ];

    protected $appends = ['full_url'];

    public function getFullUrlAttribute()
    {
        return Storage::url($this->file_path);
    }

    public function scopeBySessionCode($query, $sessionCode)
    {
        return $query->where('session_code', $sessionCode);
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
}
