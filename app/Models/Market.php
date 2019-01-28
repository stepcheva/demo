<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Market extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'address',
        'lat',
        'lng',
        'city_name',
        'city_id',
        'type',
    ];

    protected $dates = ['deleted_at'];

}
