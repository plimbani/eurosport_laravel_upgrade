<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTempTournamentCompetationTemplateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tournament_competation_template', function (Blueprint $table) {
            $table->increments('id')->unsigned(10);
            $table->integer('total_teams')->nullable();
            $table->integer('tournament_id')->unsigned()->index();
            $table->foreign('tournament_id')->references('id')->on('tournaments');
            $table->string('group_name')->nullable();
            $table->integer('tournament_template_id')->unsigned(10)->index();
            $table->foreign('tournament_template_id')->references('id')->on('tournament_template');
            $table->integer('min_matches')->nullable();
            $table->integer('total_match')->nullable();
            $table->string('category_age')->nullable();
            $table->string('disp_format_name')->nullable();
            $table->integer('total_time')->nullable();
            $table->integer('game_duration_RR')->nullable();
            $table->integer('game_duration_FM')->nullable();
            $table->integer('halftime_break_RR')->nullable();
            $table->integer('halftime_break_FM')->nullable();
            $table->integer('match_interval_RR')->nullable();
            $table->integer('match_interval_FM')->nullable();
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
        Schema::dropIfExists('tournament_competation_template');
    }
}
