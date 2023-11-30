<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AddFieldsIntoTournamentTemplateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tournament_template', function ($table) {
            $table->string('total_groups', 10)->after('total_teams')->nullable();
            $table->string('total_teams_in_round_two', 10)->after('total_groups')->nullable();
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
            $table->dropColumn('total_groups');
            $table->dropColumn('total_teams_in_round_two');
        });
    }
}
