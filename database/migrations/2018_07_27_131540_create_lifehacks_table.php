<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLifehacksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lifehacks', function (Blueprint $table) {
            $table->increments('id');
            $table->text('body');
            $table->unsignedInteger('city_id')->index();
            $table->tinyInteger('is_published')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->unsignedInteger('user_id')->index();
            $table->integer('likes')->default(0);
            $table->unsignedInteger('chapter_id');
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
        Schema::dropIfExists('lifehacks');
    }
}
