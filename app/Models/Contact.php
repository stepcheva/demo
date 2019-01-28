<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'body',
        'phone',
        'user_id',
        'email',
        'is_answered',
    ];

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function scopeIsNotAnswered($query)
    {
        return $query->where('is_answered', 0);
    }

    public function scopeIsAnswered($query)
    {
        return $query->where('is_answered', 1);
    }

}




