<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemplateSlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'template_id',
        'slot_order',
        'x',
        'y',
        'width',
        'height',
        'rotation',
        'border_style',
        'border_width',
        'border_color',
        'border_radius',
    ];

    public function template()
    {
        return $this->belongsTo(Template::class);
    }
}
