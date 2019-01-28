<?php

namespace App\Http\Controllers\Admin;

use App\Models\Recipe;
use App\Models\Lifehack;
use App\Http\Controllers\Controller;

class XmlController extends Controller
{
    public function index()
    {
        $items = Recipe::published()->orderBy('likes', 'desc')->paginate(30);
        //$lifehacks = Lifehack::oldest('published_at')->orderBy('likes')->get();
        return view('api.xml.index', compact('items'));
    }

    public function add($id)
    {
        $recipe = Recipe::find($id);
        $recipe->touchs()->create();
        session()->flash('success', 'Данные успешно добавлены.');
        return redirect()->route('xml.index');
    }

    public function destroy($id)
    {
        $recipe = Recipe::find($id);
        $recipe->touchs()->delete();
        session()->flash('success', 'Рецепт успешно удален из панели.');
        return redirect()->route('xml.index');
    }
}
