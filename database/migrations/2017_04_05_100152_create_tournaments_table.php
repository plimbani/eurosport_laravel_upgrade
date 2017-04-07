<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTournamentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tournaments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('website');
            $table->string('facebook');
            $table->string('twitter');
            $table->string('logo');
            $table->enum('competition_type', array('Group Games', 'Single Elimination', 'Double Elimination'));
            $table->enum('status', array('Published', 'UnPublished', 'Closed'));
            $table->integer('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users');
            $table->datetime('start_date');
            $table->datetime('end_date');
            $table->integer('no_of_pitches');
            $table->integer('no_of_match_per_day_pitch');
            $table->double('points_per_match_win',8,2);
            $table->double('points_per_match_tie',8,2);
            $table->double('points_per_bye',8,2);
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
        Schema::dropIfExists('tournaments');
    }
}
