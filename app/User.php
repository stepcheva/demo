<?php

namespace App;

use App\Models\City;
use App\Models\Comment;
use App\Models\Device;
use App\Models\Favourite;
use App\Models\Lifehack;
use App\Models\Qr;
use App\Models\Recipe;
use App\Models\Result;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class User extends Authenticatable implements JWTSubject
{
    use Notifiable, SoftDeletes;

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [
            'id'              => $this->id,
            'name'            => $this->name,
            'avatar'          => $this->avatar,
            'social_id'       => $this->social_id,
            'social_driver'   => $this->social_driver,
        ];
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'social_id',
        'social_driver',
        'is_admin',
        'level',
        'is_blocked',
        'is_active',
        'avatar',
        'city_id',
        'points',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $dates = ['deleted_at'];

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function devices()
    {
        return $this->hasMany(Device::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function contacts()
    {
        return $this->hasMany(Comment::class);
    }

    public function recipes()
    {
        return $this->hasMany(Recipe::class);
    }

    public function lifehacks()
    {
        return $this->hasMany(Lifehack::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function favourites()
    {
        return $this->hasMany(Favourite::class);
    }

    public function qrs()
    {
        return $this->hasMany(Qr::class);
    }

    public function isBlocked()
    {
        return $this->is_blocked == 1;
    }

    public function isActive()
    {
        return $this->is_active == 1;
    }

    public function results()
    {
        return $this->hasMany(Result::class);
    }

    public function getRecipesLikes()
    {
        return $this->recipes->map(function ($item) {
            return $item->likes;
        })->sum();
    }

    public function getLifehacksLikes()
    {
        return $this->lifehacks->map(function ($item) {
            return $item->likes;
        })->sum();
    }

    public function getQrsPoints()
    {
        return $this->qrs->map(function ($item) {
            return $item->points;
        })->sum();
    }

    public function getMonthlyRecipes($start, $end)
    {
        return $this->recipes()->whereBetween('created_at', [$start, $end])->get();
    }

    public function getMonthlyLifehacks($start, $end)
    {
        return $this->lifehacks()->whereBetween('created_at', [$start, $end])->get();
    }

    public function getMonthlyRecipesLikes($start, $end)
    {
        return $this->recipes->map(function ($item) use ($start, $end) {
            return $this->getMonthlyLikes($item, $start, $end);
        })->sum();
    }

    public function getMonthlyLifehacksLikes($start, $end)
    {
        return $this->lifehacks->map(function ($item) use ($start, $end) {
            return $this->getMonthlyLikes($item, $start, $end);
        })->sum();
    }

    public function getMonthlyLikes($item, $start, $end)
    {
        $item->likes()->whereBetween('created_at', [$start, $end])->get();
    }

    public function getMonthlyComments($start, $end)
    {
        return $this->comments()->whereBetween('created_at', [$start, $end])->get();
    }

    public function getMonthlyQrsPoints($start, $end)
    {
        return $this->getMonthlyQrs($start, $end)->map(function ($item) {
            return $item->points;
        })->sum();
    }
    public function getMonthlyQrs($start, $end)
    {
        return $this->qrs()->whereBetween('created_at', [$start, $end])->get();
    }

    public function getPointsToNextLevel()
    {
        switch ($this->level) {
            case '4':
                $pointsToNextLevel = 0 ;
                break;
            case '3':
                $pointsToNextLevel = 500 - $this->points;
                break;
            case '2':
                $pointsToNextLevel = 250 - $this->points;
                break;
            case '1':
                $pointsToNextLevel = 150 - $this->points;
                break;
            default:
                $pointsToNextLevel = 50 - $this->points;
                break;
        }

        return $pointsToNextLevel;
    }

}
