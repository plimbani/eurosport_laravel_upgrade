<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AddNewFieldsToTournamentCompetitionTemplate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tournament_competation_template', function ($table) {
            $table->integer('win_point')->after('team_interval')->nullable();
            $table->integer('loss_point')->after('win_point')->nullable();
            $table->integer('draw_point')->after('loss_point')->nullable();
            $table->text('rules')->after('draw_point')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tournament_competation_template', function ($table) {
            $table->dropColumn(['win_point', 'loss_point', 'draw_point', 'rules']);
        });
    }
}
