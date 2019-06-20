<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewFieldsToTempFixturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('temp_fixtures', function (Blueprint $table) {
            $table->string('home_yellow_cards')->nullable()->default(NULL)->after('awayteam_point');
            $table->string('away_yellow_cards')->nullable()->default(NULL)->after('home_yellow_cards');
            $table->string('home_red_cards')->nullable()->default(NULL)->after('away_yellow_cards');
            $table->string('away_red_cards')->nullable()->default(NULL)->after('home_red_cards');
            $table->string('age_category_color')->nullable()->default(NULL)->after('away_red_cards');
            $table->string('group_color')->nullable()->default(NULL)->after('age_category_color');
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
            $table->dropColumn(['home_yellow_cards', 'away_yellow_cards', 'home_red_cards', 'away_red_cards', 'age_category_color', 'group_color']);
        });
    }
}
