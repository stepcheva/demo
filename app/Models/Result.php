<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    protected $fillable = [
        'user_id',
        'points',
        'recipes',
        'lifehacks',
        'recipes_likes',
        'lifehacks_likes',
        'comments',
        'qrs_points',
        'qrs_count',
    ];

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }
}
