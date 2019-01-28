<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
            //все картинки в одной сущности
            $table->increments('id');
            $table->text('original')->nullable();
            $table->text('large')->nullable();
            $table->text('medium')->nullable();
            $table->text('small')->nullable();
            $table->unsignedInteger("imageable_id")->nullable();
            $table->string("imageable_type")->nullable();
            $table->index(["imageable_id", "imageable_type"]);
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
        Schema::dropIfExists('images');
    }
}
