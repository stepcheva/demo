<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Touchpanel extends Model
{
    protected $fillable = [
        'touchable',
    ];

    public function touchable()
    {
        return $this->morphTo();
    }
}
