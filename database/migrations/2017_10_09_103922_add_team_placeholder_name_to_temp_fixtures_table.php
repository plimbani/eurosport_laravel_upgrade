<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTeamPlaceholderNameToTempFixturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('temp_fixtures', function($table) {
            $table->string('home_team_placeholder_name')->after('home_team_name')->nullable();
            $table->string('away_team_placeholder_name')->after('away_team_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('temp_fixtures', function($table) {
            $table->dropColumn('home_team_placeholder_name');
            $table->dropColumn('away_team_placeholder_name');
        });
    }
}
