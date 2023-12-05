<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('positions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('age_category_id')->unsigned()->index();
            $table->foreign('age_category_id')->references('id')->on('tournament_competation_template')->onDelete('cascade');
            $table->integer('position')->unsigned()->index();
            $table->enum('dependent_type', ['match', 'ranking']);
            $table->string('match_number')->nullable();
            $table->enum('result_type', ['winner', 'looser'])->nullable();
            $table->string('ranking')->nullable();
            $table->integer('team_id')->unsigned()->index()->nullable();
            $table->foreign('team_id')->references('id')->on('teams')->onDelete('set null');
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
        Schema::dropIfExists('positions');
    }
}
