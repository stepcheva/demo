<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInstructionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('instructions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('step');//номер шага
            $table->text('body');
            $table->text('image_original')->nullable();
            $table->text('image_small')->nullable();
            $table->timestamps();
            $table->unsignedInteger('lifehack_id');
            $table->foreign('lifehack_id')->references('id')->on('lifehacks')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('instructions');
    }
}
