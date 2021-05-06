<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFieldsInTournamentTemplateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tournament_template', function (Blueprint $table) {
            DB::statement('ALTER TABLE tournament_template CHANGE total_teams total_teams INT(10) UNSIGNED NOT NULL');
            DB::statement('ALTER TABLE tournament_template CHANGE total_groups total_groups INT(10)');
            DB::statement('ALTER TABLE tournament_template CHANGE total_teams_in_round_two total_teams_in_round_two INT(10)');
            DB::statement('ALTER TABLE tournament_template CHANGE minimum_matches minimum_matches INT(10)');
            DB::statement('ALTER TABLE tournament_template CHANGE avg_matches avg_matches INT(10)');
            DB::statement('ALTER TABLE tournament_template CHANGE total_matches total_matches INT(10)');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tournament_template', function (Blueprint $table) {
            DB::statement('ALTER TABLE tournament_template CHANGE total_teams total_teams VARCHAR(255) NOT NULL');
            DB::statement('ALTER TABLE tournament_template CHANGE total_groups total_groups VARCHAR(255)');
            DB::statement('ALTER TABLE tournament_template CHANGE total_teams_in_round_two total_teams_in_round_two VARCHAR(255)');
            DB::statement('ALTER TABLE tournament_template CHANGE minimum_matches minimum_matches VARCHAR(255)');
            DB::statement('ALTER TABLE tournament_template CHANGE avg_matches avg_matches VARCHAR(255)');
            DB::statement('ALTER TABLE tournament_template CHANGE total_matches total_matches VARCHAR(255)');
        });
    }
}
