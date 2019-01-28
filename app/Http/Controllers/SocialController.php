<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Models\City;
use App\Http\Controllers\Controller;
use App\Models\Traits\ApiResponseTrait;
use Laravel\Socialite\Facades\Socialite;
use Exception;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class SocialController extends Controller
{
    use ApiResponseTrait;

    /**
     * Redirect to provider for authentication
     *
     * @param $driver
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectToProvider($driver)
    {
        if( ! (config()->has("services.{$driver}" ))){
            //return $this->sendFailedResponse("Драйвер {$driver} не поддерживается", 204);
            return redirect()->to(action('ApiController@error', ['error' => "Драйвер {$driver} не поддерживается"]));
        }

        try {
            return Socialite::driver($driver)->redirect();
        } catch (Exception $e){
                return redirect()->to(action('ApiController@error', ['error' => "Ошибка входа"]));
                //return $this->sendFailedResponse($e->getMessage());
        }


    }

    /**
     * Login social user
     *
     * @param string $driver
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleProviderCallback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->stateless()->user();
        } catch (Exception $e) {
            //return $this->sendFailedResponse($e->getMessage());
            return redirect()->to(action('ApiController@error', ['error' => $e->getMessage()]));
        }

        return $this->login($socialUser, $provider);
    }
    /**
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login($user, $provider)
    {
        $authUser = User::where(['social_id' => $user->id, 'social_driver' => $provider])->first();

        if ($authUser) {
            $authUser->update([
                'name' => $user->name,
                'avatar' => $user->avatar,
                'social_id' => $user->id,
            ]);
        } else {
            $authUser = User::create([
                'name' => $user->name,
                'avatar' => $user->avatar,
                'social_driver' => $provider,
                'social_id' => $user->id,
            ]);
        }

        try {
            $token = JWTAuth::fromUser($authUser);

        } catch (JWTException $e) {
            //return $this->sendFailedResponse('Could not create token', 500);
            return redirect()->to(action('ApiController@error', ['error' => $e->getMessage()]));
        }

        return redirect()->to(action('ApiController@success', $this->returnAccessData($token, $authUser)));

    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    /*
    public function refresh()
    {
        try{
            $token =  auth('api')->refresh();
        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return $this->sendFailedResponse('The token is invalid', 402);
            } else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                return $this->sendFailedResponse('Token expired', 402);
            } else if ( $e instanceof \Tymon\JWTAuth\Exceptions\JWTException) {
                return $this->sendFailedResponse('The token is invalid', 402);
            } else return $this->sendFailedResponse($e->getMessage());
        }
        return $this->sendSuccessResponse(returnAccessData($token));
    }
    */

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function returnAccessData($token, $user)
    {
        $data = [
            'magnitapp_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL()*10,
            'name' => $user->name,
            'avatar' => $user->avatar,
            'level' => $user->level,
            'points' => $user->points,
            'pointsToNextLevel' => $user->getPointsToNextLevel(),
        ];


        if (! is_null($user->city_id)) {
            $city = City::find($user->city_id);
            $data['city_id'] = $city->id;
            $data['city_name'] = $city->title;
            $data['city_area_region'] = $city->area_region;
            $data['city_lat'] = $city->lat;
            $data['city_long'] =  $city->long;
            $data['city_lower_corner'] =  $city->lower_corner;
            $data['city_upper_corner'] =  $city->upper_corner;
        }

        return $data;
    }


    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        auth('api')->logout();
        return $this->sendSuccessResponse("Successfully logged out", 200);
    }
}