<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RecipeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */

    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:191',
            'city_id' => 'required|integer|exists:cities,id',
            'category_id' => 'required|integer|exists:categories,id',
            'ingredients.*.id' => 'required|integer|exists:ingredients,id',
            'ingredients.*.amount' => 'required|string',
            'cooking_volume' => 'required|integer',
            'cooking_time' => 'required|string',
            'images.*' => 'required|mimes:png,gif,jpeg',
            'cooking.*.step' => 'required|string',
            'cooking.*.body' => 'required|string',
            'cookingImage.*' => 'mimes:png,gif,jpeg',
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Обязательное поле',
            'max' => 'Не более 191 символа'
        ];
    }

}
