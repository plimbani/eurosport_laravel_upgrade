<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTeamIntervalToTournamentCompetitionTemplates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tournament_competation_template', function($table) {
            $table->integer('team_interval')->nullable()->after('match_interval_FM');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tournament_competation_template', function($table) {
            $table->dropColumn('team_interval');
        });
    }
}
