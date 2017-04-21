<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePitchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pitches', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tournament_id')->unsigned()->index()->nullable();
            $table->foreign('tournament_id')->references('id')->on('tournaments');
            $table->string('pitch_number',100);
            $table->enum('type', array('grass', 'artificial', 'Indoor', 'Other'));
            $table->string('size',50)->nullable();
            $table->integer('venue_id')->unsigned()->index();
            $table->foreign('venue_id')->references('id')->on('venues');
            $table->integer('time_slot')->nullable();
            $table->string('availability')->nullable();
            $table->text('comment')->nullable();
            $table->string('pitch_capacity',20)->nullable();
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
        Schema::dropIfExists('pitches');
    }
}
