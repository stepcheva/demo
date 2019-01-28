<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Traits\ApiResponseTrait;
use App\Models\Traits\GetUser;
use Illuminate\Http\Request;
use App\Models\Recipe;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;


class RecipeController extends Controller
{
    use ApiResponseTrait, GetUser;

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:191',
            'category_id' => 'required|integer|exists:categories,id',
            'ingredients.*.id' => 'required|integer|exists:ingredients,id',
            'cooking_volume' => 'required|integer',
            'cooking_time' => 'required|string',
            'images' => 'required',
            'images.*' => 'mimes:png,gif,jpeg',
            'cooking.*.body' => 'required',
            'cookingImages.*' => 'mimes:png,gif,jpeg',
        ], [
            'required' => 'Обязательное поле',
            'max' => 'Не более 191 символа'
        ]);

        if ($validator->passes()) {
            $fields = $request->only('name', 'category_id', 'cooking_volume', 'cooking_time');

            $user = $this->getUser();
            $fields['city_id'] = $user->city_id;
            $fields['user_id'] = $user->id;

            $recipe = Recipe::create($fields);
            $recipe->saveIngredients($request->input('ingredients'));
            $recipe->saveImageWithUpload($request->file('images') );
            $recipe->createCook($request->input('cooking'), $request->file('cookingImages'));
            return $this->sendSuccessResponse("Рецепт успешно создан", 201);
        } else {
            return $this->sendFailedResponse('Ошибочные данные' . $validator->errors(),422);
        }
    }

    public function show($id)
    {
        $token = (JWTAuth::getToken());
        $user_id = $token ? JWTAuth::getPayload($token)->get('id') : false;

        $recipe = Recipe::find($id);
        if (is_null($recipe)) return $this->sendFailedResponse('Рецепт не найден', 405);
        if ($recipe->is_published) {
            $params['details'] = true;
            $data = $this->recipesResponseWrapper($recipe, $params, $user_id)->toArray();
            return ($this->sendSuccessResponse($data, 200));
        } else return $this->sendFailedResponse('Рецепт не опубликован', 200);
    }
}
