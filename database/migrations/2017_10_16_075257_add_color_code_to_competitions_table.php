<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AddColorCodeToCompetitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('competitions', function ($table) {
            $table->string('color_code', 100)->nullable()->default(null)->after('is_manual_override_standing');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('competitions', function ($table) {
            $table->dropColumn('color_code');
        });
    }
}
