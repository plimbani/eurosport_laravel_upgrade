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
            $table->integer('person_id')->unsigned()->unique()->index();
            $table->foreign('person_id')->references('id')->on('people');
            $table->string('username')->unique()->index();
            $table->string('email')->unique()->index();
            $table->string('password', 60);
            $table->string('token');
            $table->boolean('is_verified')->default(false);
            $table->string('timezone', 120)->nullable();
            $table->boolean('is_online')->default(false);
            $table->timestamp('last_login_time')->nullable();
            $table->boolean('is_active')->default(false);
            $table->timestamp('last_active_time')->nullable();
            $table->boolean('is_blocked')->default(false);
            $table->timestamp('blocked_time')->nullable();
            $table->integer('blocker_id')->nullable();
            $table->string('settings')->nullable();
            $table->rememberToken();
            $table->softDeletes();
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
        Schema::dropIfExists('users');
    }
}
