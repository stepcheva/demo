<?php

namespace App\Http\Controllers\Admin;

use App\Models\Result;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $items = User::where('is_admin', '=', false)->orderBy('points', 'desc');
        if ($request->has('filter')) {
            $items = $items->where('name','like', "%$request->filter%")->paginate(20);
            $filter = $request->filter;
            $items->appends('filter', $filter);
            return view('admin.users.list', ['items' => $items, 'filter' => $filter]);
        }
        return view('admin.users.list', ['items' => $items->paginate(20)]);
    }

    public function blocking(User $user)
    {
        if ($user->isBlocked()) {
            $user->update([
                'is_blocked' => 0,
                'is_active' => 1,
            ]);
            session()->flash('success', 'Пользователь успешно разблокирован.');
        } else {
            $user->update([
                'is_blocked' => 1,
                'is_active' => 0,
            ]);
            session()->flash('success', 'Пользователь заблокирован.');
        }
        return redirect()->back();
    }

    public function destroy($id)
    {
        User::destroy($id);
        session()->flash('success', 'Пользователь удален.');
        return redirect()->route('users.index');
    }

    public function changeCity(User $user)
    {
        $user->update([
            'city_id' => NULL,
        ]);
        session()->flash('success', 'Город успешно изменен.');
        return redirect()->route('users.index');
    }

    public function details(User $user)
    {
        $recipesLikes = $user->recipes->map(function ($item) {
            return $item->likes;
        })->sum();

        $lifehacksLikes = $user->lifehacks->map(function ($item) {
            return $item->likes;
        })->sum();

        $qrs = $user->qrs->map(function ($item) {
            return $item->points;
        })->sum();

        $comments = $user->comments->count();

        return view('admin.users.details', [
            'user' => $user,
            'recipesLikes' => $recipesLikes,
            'lifehacksLikes' => $lifehacksLikes,
            'qrs' => $qrs,
            'comments' => $comments,
        ]);
    }

    public function results($month)
    {
        $addMonth = $month - Carbon::now()->month;
        $start = Carbon::now()->addMonth($addMonth)->startOfMonth()->addDays(1);
        $end = Carbon::now()->addMonth($addMonth)->endOfMonth()->addDays(2);

        $items = Result::whereBetween('created_at', [$start, $end])
            ->orderBy('points', 'desc')->get();

        return view('admin.users.submenu', compact('items','month'));
    }
}
