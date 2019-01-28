<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cooking extends Model
{
    protected $fillable = [
        'step',
        'body',
        'image_original',
        'image_small',
        'recipe_id',
    ];

    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }
}
