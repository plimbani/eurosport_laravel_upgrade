<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMatchStandingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('match_standing', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tournament_id')->unsigned()->index();
            $table->foreign('tournament_id')->references('id')->on('tournaments');
            $table->integer('competition_id')->unsigned()->index();
            $table->foreign('competition_id')->references('id')->on('competitions');
            $table->integer('team_id')->unsigned()->index();
            $table->foreign('team_id')->references('id')->on('teams');
            $table->integer('points');
            $table->integer('played');
            $table->integer('won');
            $table->integer('draws');
            $table->integer('lost');
            $table->integer('goal_for');
            $table->integer('goal_against');
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
        Schema::dropIfExists('match_standing');
    }
}
