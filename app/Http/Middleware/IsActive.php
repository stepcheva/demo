<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use App\Models\Traits\ApiResponseTrait;
use Exception;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class IsActive
{
    use ApiResponseTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            $token = JWTAuth::getToken();
        } catch (JWTException $e) {
            throw new UnauthorizedHttpException('jwt-auth', $e->getMessage(), $e, $e->getCode());
        }

        $id = JWTAuth::getPayload($token)->get('id');
        $user = User::findOrFail($id);
        if ($user) {
            if($user->isBlocked())
                return $this->sendFailedResponse('Ваш аккаунт заблокирован', 402);
            if(isset($user->city_id)) {
                return $next($request);
            } else if($request->has('id')){
                $user->city_id = $request->id;
                $user->is_active = 1;
                $user->save();
                return $next($request);
            }
        } else return $this->sendFailedResponse('Пользователь не зарегистрирован', 401);
        return $this->sendFailedResponse('Город не установлен', 402.1);
    }
}
