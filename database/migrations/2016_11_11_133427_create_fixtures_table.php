<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFixturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fixtures', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tournament_id')->unsigned()->index();
            $table->foreign('tournament_id')->references('id')->on('tournaments');
            $table->integer('competition_id')->unsigned()->index();
            $table->foreign('competition_id')->references('id')->on('competitions');
            $table->integer('venue_id')->unsigned()->index();
            $table->foreign('venue_id')->references('id')->on('venues');
            $table->integer('pitch_id')->unsigned()->index();
            $table->foreign('pitch_id')->references('id')->on('pitches');
            $table->datetime('match_datetime');
            $table->unsignedInteger('match_number');
            $table->string('round');
            $table->integer('home_team')->unsigned()->index();
            $table->foreign('home_team')->references('id')->on('teams');
            $table->integer('away_team')->unsigned()->index();
            $table->foreign('away_team')->references('id')->on('teams');
            $table->tinyInteger('hometeam_score');
            $table->tinyInteger('awayteam_score');
            $table->float('hometeam_point');
            $table->float('awayteam_point');
            $table->integer('match_result_id')->unsigned()->index();
            $table->foreign('match_result_id')->references('id')->on('match_results');
            $table->text('bracket_json');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fixtures');
    }
}
