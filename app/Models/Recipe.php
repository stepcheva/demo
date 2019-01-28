<?php

namespace App\Models;

use App\Models\Traits\ImageUploadsTrait;
use App\Models\Traits\Itemable;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use ImageUploadsTrait, Itemable;

    //protected $with = ['user', 'images', 'ingredients', 'cookings', 'category'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'city_id',
        'cooking_volume',
        'cooking_time',
        'category_id',
        'is_published',
        'published_at',
        'has_vivo',
        'has_partners',
        'points',
        'from_shief',
        'user_id',
        'likes',
        'comments',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function cookings()
    {
        return $this->hasMany(Cooking::class)->orderBy('step', 'asc');
    }

    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class, 'ingredient_recipes','recipe_id', 'ingredient_id')->withPivot('amount')->withTimestamps();
    }

    public function ingredientRecipes()
    {
        return $this->hasMany(IngredientRecipe::class);
    }

    public function saveIngredients($ingredients)
    {
        foreach ($ingredients as $value) {
            $ingredient = Ingredient::find($value['id']);
            $this->ingredients()->attach($ingredient, ['amount' => $value['amount']]);
        }
        return true;
    }

    public function createCook($cookings, $cookingImages)
    {
        foreach ($cookings as $key => $value) {
            //если прилетел массив картинок
            if (! is_null($cookingImages)) {
                $file = array_key_exists($key, $cookingImages) ? $cookingImages[$key]: NULL;
                $originalImage = $file ? ImageUploadsTrait::imageUpload( $this->imageRelationPath($key + 1), $file ) : NULL;
            } else $originalImage = null;
            $this->cookings()->create([
                'step' => $key + 1,
                'body' => $value['body'],
                'image_original' =>$originalImage,
                'image_small' => ! is_null($originalImage) ? ImageUploadsTrait::resizeImage($originalImage, 150): NULL,
            ]);
        }
        return true;
    }

}
