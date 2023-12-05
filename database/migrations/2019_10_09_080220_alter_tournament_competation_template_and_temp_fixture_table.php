<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AlterTournamentCompetationTemplateAndTempFixtureTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE tournament_competation_template CHANGE team_interval minimum_team_interval int(11) NULL');
        Schema::table('tournament_competation_template', function ($table) {
            $table->integer('maximum_team_interval')->nullable()->after('minimum_team_interval');
        });
        Schema::table('temp_fixtures', function ($table) {
            $table->integer('maximum_team_interval_flag')->after('minimum_team_interval_flag')->default(0);
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
            $table->dropColumn('maximum_team_interval');
        });
        Schema::table('temp_fixtures', function ($table) {
            $table->dropColumn('maximum_team_interval_flag');
        });
        DB::statement('ALTER TABLE tournament_competation_template CHANGE minimum_team_interval team_interval int(11) NULL');
    }
}
