<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Validator;
use App\Models\Traits\GetUser;
use Illuminate\Http\Request;
use App\Models\Lifehack;
use Tymon\JWTAuth\Facades\JWTAuth;


class LifehackController extends Controller
{
    use ApiResponseTrait, GetUser;

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'chapter_id' => 'required|integer|exists:chapters,id',
            'images' => 'required',
            'images.*' => 'mimes:png,gif,jpeg',
            'instructions.*.body' => 'required',
            'instructions.*.name'=> 'required|string|max:191',
            'instructionImages.*' => 'mimes:png,gif,jpeg',
        ], [
            'required' => 'Обязательное поле',
            'max' => 'Не более 191 символа'
        ]);

        if ($validator->passes()) {
            $fields = $request->only('name', 'chapter_id');

            $user = $this->getUser();
            $fields['city_id'] = $user->city_id;
            $fields['user_id'] = $user->id;

            $lifehack = Lifehack::create($fields);
            $lifehack->saveImageWithUpload($request->file('images') );
            $lifehack->createInstructions($request->input('instructions'), $request->file('instructionImages'));
            return $this->sendSuccessResponse("Лайфхак успешно создан", 201);
        } else {
            return $this->sendFailedResponse('Ошибочные данные' . $validator->errors(),422);
        }
    }

    public function show($id)
    {
        $token = (JWTAuth::getToken());
        $user_id = $token ? JWTAuth::getPayload($token)->get('id') : false;

        $lifehack = Lifehack::find($id);
        if (is_null($lifehack)) return $this->sendFailedResponse('Лайфхак не найден', 405);
        if ($lifehack->is_published) {
            $params['details'] = true;
            $data = $this->lifehacksResponseWrapper($lifehack, $params, $user_id)->toArray();
            return ($this->sendSuccessResponse($data, 200));
        } else return $this->sendFailedResponse('Лайфхак не опубликован', 200);
    }
}
