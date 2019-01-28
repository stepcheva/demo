<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Eloquent::unguard();

        $path = database_path('seeds/files/category_chapter_ingredients.sql');

        DB::unprepared(file_get_contents($path));
        $this->command->info('Category_chapter_ingredients table seeded!');
    }
}
