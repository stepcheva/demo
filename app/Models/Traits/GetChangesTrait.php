<?php

namespace App\Models\Traits;

use App\Models\Diff;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

trait GetChangesTrait
{
    /**
     * @param $model Model
     * @param Request $request
     * @return array
     */
    public static function retrieve(Model $model, $params)
    {
        $selected = !is_null($params['selected']) ? $params['selected'] : '*';
        if (array_key_exists('hash', $params) && !is_null($params['hash'])) {
            //требуется получить изменения
            $date = DB::table('diffs')->select('created_at')->where('hash', $params['hash'])->first();
            if (!is_null($date)) {
                //корректный хэш, отдаем изменения
                if (array_key_exists('without_deleted', $params) && $params['without_deleted'])
                $changes = $model->select($selected)->where('created_at', '>=', $date->created_at)
                    ->orWhere('updated_at', '>=', $date->created_at)
                    ->get()->toArray();
                else
                    $changes = $model->withTrashed()->select($selected)->where('created_at', '>=', $date->created_at)
                        ->orWhere('updated_at', '>=', $date->created_at)
                        ->orWhere('deleted_at', '>=', $date->created_at)
                        ->get()->toArray();
            } else {
                //некорректный hash
                $changes = null;
            }
        } else {
            //отдаем все подряд
            if (array_key_exists('without_deleted', $params) && $params['without_deleted'])
                $changes = $model->select($selected)->get()->toArray();
            else
               $changes = $model->withTrashed()->select($selected)->get()->toArray();
        }

        //если отдаем изменения, то генерим хэш
        if ($changes) {
            $newHash = Diff::create(['hash' => md5(uniqid(rand(), true))]); //исключение поймать, если хэши случайно совпадут
        } else $newHash = null;
        $data['data'] =  compact('changes', 'newHash');

        return $data;
    }
}