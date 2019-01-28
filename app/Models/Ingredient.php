<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ingredient extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'is_partners',
    ];

    protected $dates = ['deleted_at'];

    public function recipes()
    {
        return $this->belongsToMany(Recipe::class)->withPivot('amount')->withTimestamps();
    }

    public function ingredientRecipes()
    {
        return $this->hasMany(IngredientRecipe::class);
    }
}
