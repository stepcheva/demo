<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecipesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recipes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->unsignedInteger('city_id')->index();
            $table->unsignedInteger('category_id')->index();

            $table->integer('cooking_volume')->nullable();
            $table->string('cooking_time',32)->nullable();

            $table->tinyInteger('is_published')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->tinyInteger('has_vivo')->default(0);
            $table->tinyInteger('has_partners')->default(0);
            $table->integer('points')->default(0);
            $table->integer('likes')->default(0);
            $table->tinyInteger('from_shief')->default(0);
            $table->unsignedInteger('user_id')->index();
            $table->timestamps();
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recipes');
    }
}
