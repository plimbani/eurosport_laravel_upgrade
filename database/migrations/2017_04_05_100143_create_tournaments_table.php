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
            $table->string('website')->nullable();
            $table->string('facebook')->nullable();
            $table->string('twitter')->nullable();
            $table->string('logo')->nullable();
            $table->enum('competition_type', array('Group Games', 'Single Elimination', 'Double Elimination'))->nullable();
            $table->enum('status', array('Published', 'Unpublished', 'Closed'))->nullable();
            $table->integer('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users');
            $table->datetime('start_date');
            $table->datetime('end_date');
            $table->integer('no_of_pitches')->nullable()->unsigned(10);
            $table->integer('no_of_match_per_day_pitch')->nullable()->unsigned(10);
            $table->double('points_per_match_win',8,2)->nullable();
            $table->double('points_per_match_tie',8,2)->nullable();
            $table->double('points_per_bye',8,2)->nullable();
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
        Schema::dropIfExists('tournaments');
    }
}
