<?php

use Illuminate\Database\Migrations\Migration;

class UpdateMinMatchFieldToTournamentCompetitionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE tournament_competation_template CHANGE min_matches min_matches int(11) NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE tournament_competation_template CHANGE min_matches min_matches int(11) NOT NULL');
    }
}
