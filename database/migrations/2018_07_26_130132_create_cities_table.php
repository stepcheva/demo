<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->integer('id');
            $table->string('title');
            $table->string('area_region')->nullable();
            $table->decimal('lat')->nullable();
            $table->decimal('long')->nullable();
            $table->string('lower_corner', 30)->nullable();
            $table->string('upper_corner', 30)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->primary('id');
            $table->index(['title', 'area_region'])->unique();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cities');
    }
}
