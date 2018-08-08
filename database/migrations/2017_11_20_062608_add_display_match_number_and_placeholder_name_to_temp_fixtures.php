<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDisplayMatchNumberAndPlaceholderNameToTempFixtures extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('temp_fixtures', function($table) {
           $table->string('display_match_number')->nullable()->after('match_number');
           $table->string('display_home_team_placeholder_name')->nullable()->after('home_team_placeholder_name');
           $table->string('display_away_team_placeholder_name')->nullable()->after('away_team_placeholder_name');
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
            $table->dropColumn('display_match_number');
            $table->dropColumn('display_home_team_placeholder_name');
            $table->dropColumn('display_away_team_placeholder_name');
        });
    }
}
