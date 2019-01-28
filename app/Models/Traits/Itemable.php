<?php
/**
 * Created by PhpStorm.
 * User: z.stepcheva
 * Date: 09.10.2018
 * Time: 16:48
 */

namespace App\Models\Traits;


use App\Models\City;
use App\Models\Comment;
use App\Models\Favourite;
use App\Models\Image;
use App\Models\Like;
use App\Models\Touchpanel;
use App\User;

trait Itemable
{
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function favourites()
    {
        return $this->morphMany(Favourite::class, 'favouriteable');
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', 1);
    }

    public function scopeOfName($query, $name)
    {
        return $query->where('name', 'like', "%$name%");
    }

    public function is_liked($user_id)
    {
        return  $this->likes()->where('user_id', $user_id)->first();
    }

    public function is_favourite($user_id)
    {
        return  $this->favourites()->where('user_id', $user_id)->first();
    }

    public function addLike($user_id)
    {
        $this->likes++;
        $this->save();
        return $this->likes()->create([
            'user_id' => $user_id,
        ]);
    }

    public function addComment($user_id, $comment)
    {
        return $this->comments()->create([
            'user_id' => $user_id,
            'body' => $comment,
        ]);
    }

    public function deleteLike($user_id)
    {
        $this->likes--;
        $this->save();
        $like = $this->is_liked($user_id);
        return $like->delete();
    }

    public function addFavourite($user_id)
    {
        return $this->favourites()->create([
            'user_id' => $user_id,
        ]);
    }

    public function deleteFavourite($user_id)
    {
        $favourite = $this->is_favourite($user_id);
        return $favourite->delete();
    }

    public function touchs()
    {
        return $this->morphMany(Touchpanel::class, 'touchable');
    }

}