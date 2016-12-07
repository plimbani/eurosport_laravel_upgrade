<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgeGroupTournamentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('age_group_tournament', function (Blueprint $table) {
            $table->integer('age_group_id')->unsigned()->index();
            $table->foreign('age_group_id')->references('id')->on('age_groups');
            $table->integer('tournament_id')->unsigned()->index();
            $table->foreign('tournament_id')->references('id')->on('tournaments');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('age_group_tournament');
    }
}
