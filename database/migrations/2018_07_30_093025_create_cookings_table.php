<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cookings', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('step');//номер шага
            $table->text('body');
            $table->text('image_original')->nullable();
            $table->text('image_small')->nullable();
            $table->timestamps();
            $table->unsignedInteger('recipe_id')->nullable();
            $table->foreign('recipe_id')->references('id')->on('recipes')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cookings');
    }
}
