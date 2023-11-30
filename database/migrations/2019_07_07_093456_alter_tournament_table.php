<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTournamentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tournament_template', function (Blueprint $table) {
            DB::statement('ALTER TABLE tournament_template CHANGE divisions no_of_divisions INT(10)');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tournament_template', function (Blueprint $table) {
            DB::statement('ALTER TABLE tournament_template CHANGE no_of_divisions divisions VARCHAR(255)');
        });
    }
}
