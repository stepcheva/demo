<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'body',
        'commentable',
        'user_id',
        'is_complained',
    ];

    public function commentable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function scopeIsComplained($query)
    {
        return $query->where('is_complained', 1);
    }

    public function scopeIsApproved($query)
    {
        return $query->where('is_complained', 0);
    }
}
