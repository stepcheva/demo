<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Recipe::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence(4),
        'city_id' => random_int(1, 100),
        'cooking_volume' => random_int(1, 20),
        'cooking_time' =>  random_int(2, 100),
        'user_id' => 1,
        'category_id' => random_int(1, 15),
    ];
});

$factory->afterCreating(App\Models\Recipe::class, function ($recipe, Faker $faker) {
    $recipe->cookings()->save(factory(App\Models\Cooking::class)->make());
    $recipe->comments()->save(factory(App\Models\Comment::class)->make());
    $recipe->images()->save(factory(App\Models\Image::class)->make());
});



