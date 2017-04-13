<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRefereeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('referee', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('availability');
            $table->integer('tournament_id')->unsigned()->index();
            $table->foreign('tournament_id')->references('id')->on('tournaments');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('telephone')->nullable();
            $table->string('email')->nullable();
            $table->text('comments');
            $table->integer('age_group_id');
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
        Schema::dropIfExists('referee');
    }
}
