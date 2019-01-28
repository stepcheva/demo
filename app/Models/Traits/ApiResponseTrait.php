<?php

namespace App\Models\Traits;

use App\Models\Lifehack;
use App\Models\Recipe;
use App\User;
use Illuminate\Http\Request;

trait ApiResponseTrait
{
    /**
     * Send a failed response with a msg
     *
     * @param  string $message
     * @param  $status
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendFailedResponse($message, $status = null, $result = false)
    {
        return response()->json([
            'message' => $message,
            'status' => $status,
            'result' => $result,
        ]);
    }

    /**
     * Send a successful response
     *
     * @param array $data
     * @param $status
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendSuccessResponse($data, $status = null, $result = true)
    {
        return response()->json([
            'data' => $data,
            'status' => $status,
            'result' => $result,
        ]);
    }

    public function recipesResponseWrapper(Recipe $recipe, $params = [], $user_id = false)
    {
        //убрать в модели в скоупы
        $data = collect($recipe)->only(['id', 'name', 'city_id', 'category_id', 'cooking_volume', 'cooking_time', 'published_at', 'likes']);
        $data->put('comments', $recipe->comments()->isApproved()->count());

        if (array_key_exists('posts', $params)) {
            //для списка постов пользователя отдаем одну картинку
            $data->put('images', $this->getFullImageUrl($recipe));
            return $data;
        }

        if (array_key_exists('details', $params)) {
            //для детальной страницы
            $data->put('details', true);
            $data->put('ingredients', $recipe->ingredients()->withTrashed()->get(['ingredient_id', 'amount']));
            $data->put('cooking', $this->getRelated($recipe->cookings()));
        }

        $this->addBaseInfo($data, $recipe, $user_id);

        return $data;
    }

    public function lifehacksResponseWrapper(Lifehack $lifehack, $params = [], $user_id = false)
    {
        //убрать в модели в скоупы
        $data = collect($lifehack)->only(['id', 'name', 'city_id', 'chapter_id', 'published_at', 'likes']);
        $data->put('comments', $lifehack->comments()->isApproved()->count());

        if (array_key_exists('posts', $params)) {
            //для списка постов пользователя отдаем одну картинку
            $data->put('images', $this->getFullImageUrl($lifehack));
            return $data;
        }

        if (array_key_exists('details', $params)) {
            //для детальной страницы
            $data->put('details', true);
            $data->put('instructions', $this->getLifehacksRelated($lifehack->instructions()->orderBy('step')));
        }

        $this->addBaseInfo($data, $lifehack, $user_id);

        return $data;
    }

    ///////////////////////////////////////////////////////////////

    public function getFullImageUrl($model)
    {
        $image = $model->images()->get(['original', 'large', 'medium', 'small'])->first();
        if (!is_null($image)) {
            $image = collect($image->getAttributes())->map(function ($it) {
                $it = env('APP_IMAGE_URL') . $it;
                return $it;
            });
        }
        return $image;
    }

    public function getRelated($related)
    {
        return $related->get(['step', 'body', 'image_original', 'image_small'])->map(function ($item) {
            if (!is_null($item->image_original)) {
                $item->image_original = env('APP_IMAGE_URL') . $item->image_original;
            }
            if (!is_null($item->image_small)) {
                $item->image_small = env('APP_IMAGE_URL') . $item->image_small;
            }
            return $item;
        });
    }

    public function getLifehacksRelated($related)
    {
        return $related->get(['step', 'name','body', 'image_original', 'image_large'])->map(function ($item) {
            if (!is_null($item->image_original)) {
                $item->image_original = env('APP_IMAGE_URL') . $item->image_original;
            }
            if (!is_null($item->image_large)) {
                $item->image_large = env('APP_IMAGE_URL') . $item->image_large;
            }
            return $item;
        });
    }

    public function getImages($model)
    {
        return $model->images()->get(['original', 'large', 'medium', 'small'])->map(function ($item) {
            return collect($item->getAttributes())->map(function ($it) {
                $it = env('APP_IMAGE_URL') . $it;
                return $it;
            });
        });
    }

    public function getUserInfo($model)
    {
        return $model->user()->withTrashed()->first()->only(['id', 'name', 'avatar', 'level', 'points']);
    }

    public function getIsLiked($model, $user_id)
    {
        return ($user_id) ? ($model->is_liked($user_id) || false) : false;
    }

    public function getIsFavourited($model, $user_id)
    {
        return ($user_id) ? ($model->is_favourite($user_id) || false) : false;
    }

    public function addBaseInfo($data, $model, $user_id)
    {
        $data->put('images', $this->getImages($model));
        $data->put('user', $this->getUserInfo($model));
        $data->put('is_liked', $this->getIsLiked($model, $user_id));
        $data->put('is_favourite', $this->getIsFavourited($model, $user_id));
        return $data;
    }
}