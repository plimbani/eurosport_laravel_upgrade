.<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTempFixturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temp_fixtures', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tournament_id')->unsigned()->index();
            $table->foreign('tournament_id')->references('id')->on('tournaments')->onDelete('cascade');
            $table->integer('competition_id')->unsigned()->index();
            $table->foreign('competition_id')->references('id')->on('competitions')->onDelete('cascade');
            $table->integer('venue_id')->unsigned()->nullable()->nullable()->index();
            $table->integer('pitch_id')->unsigned()->nullable()->index();
            $table->datetime('match_datetime')->nullable();
            $table->string('match_number')->nullable();
            $table->string('round')->nullable();
            $table->string('home_team')->nullable();
            $table->string('away_team')->nullable();
            $table->tinyInteger('hometeam_score')->nullable();
            $table->tinyInteger('awayteam_score')->nullable();
            $table->double('hometeam_point',8,2)->nullable();
            $table->integer('match_result_id')->unsigned()->nullable()->index();
            $table->double('awayteam_point',8,2)->nullable();
            $table->text('bracket_json')->nullable();
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
        Schema::dropIfExists('temp_fixtures');
    }
}
