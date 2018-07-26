<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMatchResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('match_results', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('goal_score1');
            $table->integer('goal_score2');
            $table->enum('match_status', array('Walk over', 'abandoned', 'full-time', 'penalties'));
            $table->string('winner');
             $table->integer('location_id')->unsigned()->index();
            $table->foreign('location_id')->references('id')->on('venues');
            $table->integer('referee_id')->unsigned()->index();
            $table->foreign('referee_id')->references('id')->on('referee');
            $table->text('notes');
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
        Schema::dropIfExists('match_results');
    }
}
