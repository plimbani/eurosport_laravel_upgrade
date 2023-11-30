<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AddPitchSizeToTournamentCompetationTemplate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tournament_competation_template', function ($table) {
            $table->string('pitch_size', 100)->nullable()->after('category_age');
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
            $table->dropColumn('pitch_size');
        });
    }
}
