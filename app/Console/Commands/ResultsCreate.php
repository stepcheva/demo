<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use Carbon\Carbon;

class ResultsCreate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'results:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creating users results';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $start = Carbon::now()->addMonth(-1)->startOfMonth();
        $end = Carbon::now()->addMonth(-1)->endOfMonth();

        $users = User::where('points', '>', 0)->where('is_admin', '<', 1)->get();
        foreach ($users as $user) {
            $user->results()->create([
                'points' => $user->points,
                'recipes' => $user->getMonthlyRecipes($start, $end)->count(),
                'lifehacks' => $user->getMonthlyLifehacks($start, $end)->count(),
                'recipes_likes' => $user->getMonthlyRecipesLikes($start, $end),
                'lifehacks_likes' => $user->getMonthlyLifehacksLikes($start, $end),
                'comments' => $user->getMonthlyComments($start, $end)->count(),
                'qrs_points' => $user->getMonthlyQrsPoints($start, $end),
                'qrs_count' => $user->getMonthlyQrs($start, $end)->count(),
            ]);

           //delete points
           $user->update([
               'points' => 0,
               'level' => 0,
           ]);
        }
    }
}
