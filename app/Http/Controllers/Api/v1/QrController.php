<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Partner;
use App\Models\Productgroup;
use App\Models\Traits\ApiResponseTrait;
use App\Models\Traits\GetQrTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Jobs\QrCreate;
use Illuminate\Support\Facades\Validator;


class QrController extends Controller
{
    use GetQrTrait, ApiResponseTrait;

    public function __construct()
    {
        //проверить кэш и обновить ее
        if (! Cache::has("products")) {
           $this->setProductsToRedis(new Partner());
        }

        if (! Cache::has("product_groups")) {
            $this->setProductsToRedis(new Productgroup());
        }
    }

    public function setProductsToRedis($model)
    {
        $products = $model->orderBy('name')->get();
        if (! empty($products)) {
            $products->map(function ($item) {
                $key_prefix = $item->prefix;
                $key = mb_substr($item->name, 0, 30);
                Cache::put("$key_prefix:$key", true, 50000);
                return true;
            });
        }
       return true;
    }

    public function addQr(Request $request)
    {
        if ($request->has(['fn', 'i', 'fp'])) {

            $validator = Validator::make($request->only(['fn', 'i', 'fp']), [
                'fn' => 'required|numeric',
                'i' => 'required|numeric',
                'fp' => 'required|numeric',
            ], [
                'required' => 'Обязательное поле',
                'numeric' => 'Числовые данные'
            ]);


            if ($validator->passes()) {
                $fd = $request->input('i');
                $fn = $request->input('fn');
                $fs = $request->input('fp');

                $id = implode('.', [
                    $fn,
                    $fd,
                    $fs
                ]);

                $user_id = auth('api')->id();

                if (!Cache::has("qrs:$id")) {
                    Cache::add("qrs:$id", $user_id, 44640 * 4);
                    QrCreate::dispatch($fn, $fs, $fd, $user_id);
		    return $this->sendSuccessResponse('Чек принят к обработке', 200);
                } else return $this->sendFailedResponse('Чек уже использован', 405);

            } else return $this->sendFailedResponse('Чек не валиден' . $validator->errors(),422);
        } else return $this->sendFailedResponse('Чек не валиден' ,422);
    }
}
