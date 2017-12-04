<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHalvesRrFmToTournamentCompetitionsTemplate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
          Schema::table('tournament_competation_template', function($table) {
           $table->integer('halves_RR')->nullable()->after('game_duration_RR');
           $table->integer('halves_FM')->nullable()->after('game_duration_FM');
        });
    
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::table('tournament_competation_template', function($table) {
            $table->dropColumn('halves_RR');
            $table->dropColumn('halves_FM');
        });
    }
}
