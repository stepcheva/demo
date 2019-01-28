<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\v1\TapePaginator;
use App\Models\City;
use App\Models\Market;
use App\Models\Partner;
use App\Models\Recipe;
use App\Models\Touchpanel;
use App\Models\Traits\ApiResponseTrait;
use App\Models\Traits\CityTrait;
use App\Models\Traits\CreateTape;
use App\Models\Traits\GetUser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Traits\GetChangesTrait;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Pagination\LengthAwarePaginator;


class ApiController extends Controller
{

    use GetChangesTrait, ApiResponseTrait, GetUser, CityTrait, CreateTape;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function createXML()
    {
        $recipes = Touchpanel::get(['touchable_type', 'touchable_id'])->map(function ($touchable) {
            $model = new $touchable->touchable_type;
            return $model::find($touchable->touchable_id);
        });

        $content = View::make('api.xml.panel')->with(compact('recipes'))->render();
        return Response::make($content,'200')->header('Content-Type', 'text/xml');
    }

    public function success(Request $request)
    {
        return view('api.auth.success');
    }

    public function error(Request $request)
    {
        return view('api.auth.error', ['error' => $request->error]);
    }

    public function index(Request $request)
    {
        $token = (JWTAuth::getToken());
        $user_id = $token ? JWTAuth::getPayload($token)->get('id') : false;

        $tape = $this->getTape($request, $user_id);

        $total = $tape->count();

        if (!is_null($total)) {

            $perPage = $request->has('number') ? intval($request->input('number')) : 5;

            $page = intval($request->input('page'));
            $paginate = new TapePaginator(array_slice($tape->toArray(), ($page - 1) * $perPage, $perPage),
                count($tape), $perPage, $page, ["path" => "/"]);

            $paginate = $paginate->toArray();

            $page = $this->getPageFromChieff();

            return $this->sendSuccessResponse(compact('paginate', 'page'), 200);
        } else return $this->sendFailedResponse('Записи не найдены', 405);
    }

    public function getPageFromChieff()
    {
        return [
            'url' => env('APP_URL') . "pages/second.html?v=11",
            'image' => env('APP_URL') . 'dist/img/first/image.png',
            'text' => 'Победители, откликнитесь!',
        ];
    }

    public function getChanges($dataSource, Request $request)
    {
        if (!$this->isSupportedDataSource($dataSource)) throw new \Exception();
        $selected = $request->input('selected') ? explode(',', $request->input('selected')) : null;
        $hash = $request->input('hash') ? $request->input('hash') : null;
        $without_deleted = $request->input('without_deleted') ? $request->input('without_deleted') : null;
        $params = compact('selected', 'without_deleted', 'hash');
        $data = $this->retrieve($this->getModelByDataSource($dataSource), $params);
        return $this->sendSuccessResponse($data, 200);
    }

    protected function isSupportedDataSource($dataSource)
    {
        return in_array($dataSource, [
            'chapters',
            'categories',
            'ingredients',
            'recipes',
            'markets',
            'partners',
            'comments'
        ]);
    }

    protected function getModelByDataSource($dataSource)
    {
        $className = 'App\Models\\' . studly_case(str_singular($dataSource));
        $model = class_exists($className) ? new $className : null;
        return $model;
    }

    public function getMarkets(Request $request)
    {
        if ($request->has('type')) {
            $search['type'] = $request->input('type');
        }

        if ($request->has('city_id')) {
            $city = City::find(intval($request->input('city_id')));
            $search['city_name'] = $city->title;
        }
        if (empty($search))
            $markets = Market::all();
        else $markets = Market::where($search)->orderBy('address')->get();

        return $this->sendSuccessResponse($markets, 400);
    }

    //сбросить город (для админки и теста)
    public function setCity(Request $request)
    {
        $user = $this->getUser();

        $city_id = $request->input('id');
        $city = City::find($city_id);
        if (!is_null($city)) {
            $user->city_id = $city->id;
            $user->is_active = 1;
            $user->save();
            return $this->sendSuccessResponse('Город успешно изменен', 200);
        } else return $this->sendFailedResponse('Город не найден', 405);
    }

}
