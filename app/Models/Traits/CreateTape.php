<?php
namespace App\Models\Traits;


use App\Models\Ingredient;
use App\Models\IngredientRecipe;
use App\Models\Lifehack;
use App\Models\Recipe;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

trait CreateTape
{
    public function getTape(Request $request, $user_id)
    {
        if($request->has('model')) {
            $data_source = $request->input('model');
            $model = $this->getModelByDataSource($data_source);
            $tape = $this->createTapeFromModel($model, $request, $user_id);
        } else {
            if ($request->has('cities')) {
                if ($request->has('name')) {
                    $tape = $this->createTapeWithName($user_id, $request->input('cities'), $request->input('name'));
                } else {
                    $tape = $this->createTape($user_id, $request->input('cities'));
                }
            } else {
                $tape = ($request->has('name')) ? $this->createTapeWithName($user_id, '', $request->input('name')) : $this->createTape($user_id);
            }
        }
        return $tape;
    }

    public function createTapeFromModel($model, Request $request, $user_id)
    {
        //если есть ингредиенты -- фильтруем по ним и игнорируем все если их нет.
        if ($request->has('ingredients_included') || $request->has('ingredients_excluded')) {

            if ($request->has('ingredients_included')) {
                //$search['ingredients_included'] = explode(',', $request->input('ingredients_included'));
                $search['ingredients_included'] = $request->input('ingredients_included');
                $query = 'select DISTINCT recipe_id from ingredient_recipes where ingredient_id in ('.$search['ingredients_included'].')';
                $included = DB::select($query);
                //$included = IngredientRecipe::whereIn('ingredient_id', $search['ingredients_included'])->get(['recipe_id'])->toArray();
                $included = array_pluck($included, 'recipe_id');
            } else $included = [];

            if ($request->has('ingredients_excluded')) {
                //$search['ingredients_excluded'] = explode(',', $request->input('ingredients_excluded'));
                $search['ingredients_excluded'] = $request->input('ingredients_excluded');
                $query = 'select id from recipes where id NOT IN (select DISTINCT recipe_id from ingredient_recipes where ingredient_id in ('.$search['ingredients_excluded'].'))';
                $excluded = DB::select($query);
                //$excluded = IngredientRecipe::whereNotIn('ingredient_id', $search['ingredients_excluded'])->get(['recipe_id'])->toArray();
                $excluded = array_pluck($excluded, 'id');
                //dd($excluded);
            } else $excluded = [];

            $ingredients = array_unique(array_merge($included, $excluded));

            if (empty($ingredients)) {
                $tape = collect([]);
                return $tape;
            }

            $result = $model->whereIn('id', $ingredients)->published();

        } else $result = $model->published();

        //Работаем с коллекцией опубликованных рецептов или лайфхаков
        if($request->has('cities') && !(empty($request->input('cities')))) {
            $search['cities'] = explode(',' , $request->input('cities'));
            $result = $result->whereIn('city_id', $search['cities']);
        }

        if($request->has('name')) {
            $name = $request->input('name');
            $result = $result->ofName($name);
        }

        if($model instanceof Recipe) {
            if($request->has('categories') && !empty($request->input('categories'))) {
                $search['categories'] = explode(',' , $request->input('categories'));
                $result =  $result->whereIn('category_id', $search['categories']);
            }
        } else {
            if($request->has('chapters') && !(empty($request->input('cities')))){
                $search['chapters'] =  explode(',' , $request->input('chapters'));
                $result =  $result->whereIn('chapter_id', $search['chapters']);
            }
        }
        $result = $result->orderBy('published_at', 'desc')->get();

        $tape = $result->map(function ($item) use ($user_id, $model) {
            $data = ($model instanceof Recipe) ? $this->recipesResponseWrapper($item, $params = [], $user_id) : $this->lifehacksResponseWrapper($item, $params = [], $user_id);
            $data->put('model', $model->getTable());
            return $data;
        });

        return $tape;
    }

    //сформировать ленту
    public function createTape($user_id, $cities = '')
    {
        if(empty($cities)) {
            $table = DB::select('select * from (
                select r.id, r.published_at, "recipes" as model from recipes as r where r.is_published = 1
                union select l.id, l.published_at, "lifehacks" as model from lifehacks as l where l.is_published = 1
                ) as g ORDER BY published_at DESC');
        } else {
            //$cities = implode(",", $cities);
            $table = DB::select('select * from (
                select r.id, r.published_at, "recipes" as model from recipes as r 
                where r.is_published = 1 
                and r.city_id in (' . $cities . ') union select l.id, l.published_at, "lifehacks" as model from lifehacks as l 
                where l.is_published = 1 and l.city_id in (' . $cities . ')
                ) as g ORDER BY published_at DESC');
        }

        return $this->makeTapeFromTable($table, $user_id);
    }

    public function createTapeWithName($user_id, $cities = '', $name)
    {
        if(empty($cities)) {
            $table = DB::select('select * from (
                select r.id, r.name, r.published_at, "recipes" as model from recipes as r where r.is_published = 1
                union select l.id, l.name, l.published_at, "lifehacks" as model from lifehacks as l where l.is_published = 1 
                ) as g  WHERE g.name LIKE "%'.$name.'%" ORDER BY published_at DESC');
        } else {
            //$cities = implode(",", $cities);
            $table = DB::select('select * from (
                select r.id, r.name, r.published_at, "recipes" as model from recipes as r 
                where r.is_published = 1 
                and r.city_id in (' . $cities . ') union select l.id, l.name, l.published_at, "lifehacks" as model from lifehacks as l 
                where l.is_published = 1 and l.city_id in (' . $cities . ')
                ) as g WHERE g.name LIKE "%'.$name.'%" ORDER BY published_at DESC');
        }

       return $this->makeTapeFromTable($table, $user_id);
    }

    public function makeTapeFromTable($table, $user_id)
    {
        $tape = collect($table)->map(function ($item) use ($user_id) {
            $model = $this->getModelByDataSource($item->model)->find($item->id);
            $data = ($model instanceof Recipe) ? $this->recipesResponseWrapper($model, $params = [], $user_id) : $this->lifehacksResponseWrapper($model, $params = [], $user_id);
            $data->put('model', $item->model);
            return $data;
        }, $table);

        return $tape;
    }
}