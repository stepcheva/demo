<?php

namespace App\Observers;

use App\Models\Qr;
use App\User;

class QrObserver
{
    /**
     * Handle the qr "created" event.
     *
     * @param  \App\Models\Qr  $qr
     * @return void
     */
    public function created(Qr $qr)
    {
        $user = User::find($qr->user_id);
        $user->points += $qr->points;
        $user->save();
        return true;
    }

    /**
     * Handle the qr "updated" event.
     *
     * @param  \App\Models\Qr  $qr
     * @return void
     */
    public function updated(Qr $qr)
    {
        //
    }

    /**
     * Handle the qr "deleted" event.
     *
     * @param  \App\Models\Qr  $qr
     * @return void
     */
    public function deleted(Qr $qr)
    {
        //
    }

    /**
     * Handle the qr "restored" event.
     *
     * @param  \App\Models\Qr  $qr
     * @return void
     */
    public function restored(Qr $qr)
    {
        //
    }

    /**
     * Handle the qr "force deleted" event.
     *
     * @param  \App\Models\Qr  $qr
     * @return void
     */
    public function forceDeleted(Qr $qr)
    {
        //
    }
}
