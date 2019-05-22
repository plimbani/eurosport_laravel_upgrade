<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddScoreLastUpdateDateTimeFieldToTempFixturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('temp_fixtures', function (Blueprint $table) {
            $table->datetime('score_last_update_date_time')->nullable()->default(NULL)->after('bracket_json');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('temp_fixtures', function (Blueprint $table) {
            $table->dropColumn('score_last_update_date_time');
        });
    }
}
