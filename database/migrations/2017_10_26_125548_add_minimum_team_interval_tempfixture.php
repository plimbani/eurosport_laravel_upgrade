<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AddMinimumTeamIntervalTempfixture extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('temp_fixtures', function ($table) {
            $table->integer('minimum_team_interval_flag')->after('round')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('temp_fixtures', function ($table) {
            $table->dropColumn('minimum_team_interval_flag');
        });
    }
}
