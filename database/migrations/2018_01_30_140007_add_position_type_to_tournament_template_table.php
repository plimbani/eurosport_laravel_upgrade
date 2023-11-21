<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AddPositionTypeToTournamentTemplateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tournament_template', function ($table) {
            $table->enum('position_type', ['final', 'final_and_group_ranking', 'group_ranking'])->after('minimum_matches');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tournament_template', function ($table) {
            $table->dropColumn('position_type');
        });
    }
}
