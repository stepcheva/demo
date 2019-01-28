<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            //admin
            $table->string('email', 191)->nullable();
            $table->string('password')->nullable();
            $table->string('name');

            //socialUser
            $table->string('avatar')->nullable();
            $table->unsignedBigInteger('social_id')->nullable(); //admins не имеют
            $table->string('social_driver', 20)->nullable();//admins не имеют
            $table->index(['social_id', 'social_driver'])->unique();

            $table->tinyInteger('is_active')->default(0);//
            $table->tinyInteger('level')->default(0);//
            $table->unsignedInteger('points')->default(0)->index();
            $table->string('city_id')->nullable();     //admins не имеют

            $table->tinyInteger('is_blocked')->default(0); //admins не имеют
            $table->tinyInteger('is_admin')->default(0);
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes(); // категории не удаляем жестко

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
