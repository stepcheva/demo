<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\ImageUploadsTrait;

class Image extends Model
{
    use ImageUploadsTrait;

    protected $fillable = [
        'original',
        'imageable',
        'large',
        'medium',
        'small',
    ];

    public function imageable()
    {
        return $this->morphTo();
    }

    public function cooking()
    {
        return $this->belongsTo(Cooking::class);
    }

    public function instruction()
    {
        return $this->belongsTo(Instruction::class);
    }


}
