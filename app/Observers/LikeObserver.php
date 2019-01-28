<?php

namespace App\Observers;

use App\Models\Like;
use App\User;
use Illuminate\Support\Carbon;

class LikeObserver
{
    public $date = '2018-11-30 00:00:00';

    /**
     * Handle the like "created" event.
     *
     * @param  \App\Like $like
     * @return void
     */
    public function created(Like $like)
    {
        if ($like->created_at <= Carbon::createFromFormat('Y-m-d H:i:s', $this->date)) {
            $model = new $like->likeable_type;
            $item = $model::find($like->likeable_id);
            if ($item->user) {
                $user = User::find($item->user_id);
                $user->points++;
                $user->save();
            }
        }
        return true;
    }

    /**
     * Handle the like "updated" event.
     *
     * @param  \App\Like $like
     * @return void
     */
    public function updated(Like $like)
    {
        //
    }

    /**
     * Handle the like "deleted" event.
     *
     * @param  \App\Like $like
     * @return void
     */
    public function deleted(Like $like)
    {
        if ($like->created_at <= Carbon::createFromFormat('Y-m-d H:i:s', $this->date)) {
            $model = new $like->likeable_type;
            $item = $model::find($like->likeable_id);
            if ($item->user) {
                $user = User::find($item->user_id);
                if($user->points > 0) {
                    $user->points--;
                    $user->save();
                }
            }
        }
        return true;
    }

    /**
     * Handle the like "restored" event.
     *
     * @param  \App\Like $like
     * @return void
     */
    public function restored(Like $like)
    {
        //
    }

    /**
     * Handle the like "force deleted" event.
     *
     * @param  \App\Like $like
     * @return void
     */
    public function forceDeleted(Like $like)
    {
        //
    }
}
