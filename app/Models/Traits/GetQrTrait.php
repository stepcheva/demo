<?php

namespace App\Models\Traits;

use App\Models\Qr;
use Curl\Curl;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Cache;
use Exception;

trait GetQrTrait
{
    public function getQrProduct($fn, $fs, $fd) {

        $url = sprintf('https://check.ofd-magnit.ru/CheckWebApp/fds.zul?fn=%s&fs=%s&fd=%s',
            $fn,
            $fs,
            $fd
        );

        //Запрос страницы
        $c = (new Curl());
        $c->get($url);
        //достаем из старницы участок массива где есть данные о товарах
        $re = '/recieptItemsGrid\'[^\[]+(.+?),\n\[\'zul.grid.Grid\'/is';
        preg_match($re, $c->response, $matches);


        // Теперь нужно сделать json строку
        // сначала заменяем одинарные кавычики на двойны, после чего убираем переносы строк
        // далее все ключи хэша закавычиваем так как если этого не сделать будет syntax error
        $str = array_pop($matches);

        $str =  '[' . str_replace(
                ["'", "\n"],
                ['"',""],
                $str
            );
//        dd($str);
//        $str =  preg_replace("/([\{,])([^\"{,][^:\"]*)(:)/is", '$1"$2"$3', $str);
        $str =  preg_replace("/([\{,]\s*)([^\"{,]*)(:(?:\s*\"[^\"]+\")?)/is", '$1"$2"$3', $str);

        //получаем json
        $json = (json_decode($str));
        if($json === null) throw new \Exception("Can`t json_decode response");

        //достаем строки в массиве ответа
        if (! isset($json[0][0][4])) throw new \Exception("No valid data");
        $rows = $json[0][0][4];


        $object = new \stdClass();
        $object->id = implode('.', [
            $fn,
            $fd,
            $fs
        ]);

        //перебираем все строки
        foreach ($rows as $i => $row){

            //первая строка это заголовочные данные игнорирем их
            if($i == 0) continue;
            $object->products[] = [
                'name'  => $row[4][0][2]->value,
                'count' => $row[4][1][4][0][2]->value,
            ];
        }
        return $object;
    }

    public function getPoints($products, $points = 0, $basePoint = 4)
    {
        foreach ($products as $product) {
            $key = mb_substr($product['name'], 0, 30);
            $pointsForProduct = intval(Cache::has("products:$key"));
            $pointsForGroup = intval(Cache::has("product_groups:$key"));
            $points += intval($product['count']) * max($basePoint * ($pointsForProduct), $pointsForGroup);
        }
        return $points;
    }

}