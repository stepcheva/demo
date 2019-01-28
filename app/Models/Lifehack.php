<?php

namespace App\Models;

use App\Models\Traits\ImageUploadsTrait;
use App\Models\Traits\Itemable;
use Illuminate\Database\Eloquent\Model;

class Lifehack extends Model
{
    use ImageUploadsTrait, Itemable;

    //protected $with = ['user', 'images', 'instructions', 'chapter'];

    protected $fillable = [
        'name',
        'city_id',
        'is_published',
        'published_at',
        'user_id',
        'likes',
        'chapter_id',
        'comments',
    ];

    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }

    public function instructions()
    {
        return $this->hasMany(Instruction::class)->orderBy('step', 'asc');
    }

    public function createInstructions($instructions, $instructionImages)
    {
        foreach ($instructions as $key => $value) {
            //$file = ! is_null($cookingImages[$key]) ? $cookingImages[$key]: NULL;
            //$originalImage = $file ? ImageUploadsTrait::imageUpload( $this->imageRelationPath($key + 1), $file ) : NULL;
            if (! is_null($instructionImages)) {
                $file = array_key_exists($key, $instructionImages) ? $instructionImages[$key]: NULL;
                $originalImage = $file ? ImageUploadsTrait::imageUpload( $this->imageRelationPath($key + 1), $file ) : NULL;
            } else $originalImage = null;
            $this->instructions()->create([
                'step' => ($key + 1),
                'name' => $value['name'],
                'body' => $value['body'],
                'image_original' => $originalImage,
                'image_small' => ! is_null($originalImage) ? ImageUploadsTrait::resizeImage($originalImage, 150): NULL,
                'image_large' => ! is_null($originalImage) ? ImageUploadsTrait::resizeImage($originalImage, 300): NULL,
            ]);
        }
        return true;
    }

}
