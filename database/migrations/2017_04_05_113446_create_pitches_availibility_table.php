<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePitchesAvailibilityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pitch_availibility', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tournament_id')->unsigned()->index();
            $table->foreign('tournament_id')->references('id')->on('tournaments');
            $table->integer('pitch_id')->unsigned()->index();
            $table->foreign('pitch_id')->references('id')->on('pitches');
            $table->integer('stage_no')->default('1');
            $table->date('stage_start_date');
            $table->string('stage_start_time');
            $table->string('stage_end_time');
            $table->date('stage_continue_date');
            $table->string('break_start_time');
            $table->string('break_end_time');
            $table->date('stage_end_date');
            $table->float('stage_capacity',10,2);
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
        Schema::dropIfExists('pitch_availibility');
    }
}
