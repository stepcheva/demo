<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Qr extends Model
{
    protected $fillable = [
            'key',
            'user_id',
            'points',
        ];

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }
}
