<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
            'name',
            'qr',
        ];

    public function qr()
    {
        return $this->belongsTo(\App\Models\Qr::class);
    }
}
