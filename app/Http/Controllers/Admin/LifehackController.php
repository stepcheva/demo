<?php

namespace App\Http\Controllers\Admin;

use App\Models\Lifehack;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Models\Traits\ImageUploadsTrait;

class LifehackController extends Controller
{
    use ImageUploadsTrait;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $items = Lifehack::oldest('published_at');
        if ($request->has('filter')) {
            $items = $items->where('name','like', "%$request->filter%")->paginate(15);
            $filter = $request->filter;
            $items->appends('filter', $filter);
            return view('admin.list', ['items' => $items, 'filter' => $filter]);
        }

        return view('admin.list', ['items' => $items->paginate(15)]);
    }

    public function show($id)
    {
        $lifehack = Lifehack::findOrFail($id);
        $chapter = $lifehack->chapter()->withTrashed()->first();
        $city = $lifehack->city()->withTrashed()->first();
        $user = $lifehack->user()->withTrashed()->first();
        return view('admin.lifehacks.show', [
            'lifehack' => $lifehack,
            'city' => $city,
            'chapter' => $chapter,
            'user' => $user
        ]);
    }

    public function update(Lifehack $lifehack, Request $request)
    {
        $lifehack->update([
            'is_published' => 1,
            'published_at' => Carbon::now(),
        ]);

        session()->flash('success', 'Лайфхак успешно опубликован.');
        return redirect(route('lifehacks.index'));
    }

    public function destroy($id)
    {
        $lifehack = Lifehack::findOrFail($id);
        if (isset($lifehack)) {
            $lifehack->instructions()->delete();
	    if ($lifehack->images()->count()) {
                $path = "upload/lifehacks/$id";
                Storage::disk('public')->deleteDirectory($path);
            }
            $lifehack->images()->delete();
            $lifehack->likes()->delete();
            $lifehack->comments()->delete();
            Lifehack::destroy($id);
            session()->flash('success', 'Лайфхак успешно удален.');
            return redirect(route('lifehacks.index'));
        }
    }
}
