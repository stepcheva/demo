<?php

namespace App\Providers;

use App\Models\Comment;
use App\Models\Like;
use App\Models\Qr;
use App\Observers\CommentObserver;
use App\Observers\LikeObserver;
use App\Observers\QrObserver;
use App\Observers\UserObserver;
use App\User;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Like::observe(LikeObserver::class);
        User::observe(UserObserver::class);
        Qr::observe(QrObserver::class);
        Comment::observe(CommentObserver::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
