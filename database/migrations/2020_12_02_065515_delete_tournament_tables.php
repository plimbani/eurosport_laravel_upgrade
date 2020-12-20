<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteTournamentTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('clubs');
        Schema::dropIfExists('competitions');
        Schema::dropIfExists('fixtures');
        Schema::dropIfExists('match_results');
        Schema::dropIfExists('match_standing');
        Schema::dropIfExists('message_recipients');
        Schema::dropIfExists('messages');
        Schema::dropIfExists('pitch_availibility');
        Schema::dropIfExists('pitch_breaks');
        Schema::dropIfExists('pitch_unavailable');
        Schema::dropIfExists('pitches');
        Schema::dropIfExists('positions');
        Schema::dropIfExists('referee');
        Schema::dropIfExists('team_manual_ranking');
        Schema::dropIfExists('teams');
        Schema::dropIfExists('temp_fixtures');
        Schema::dropIfExists('tournament_club');
        Schema::dropIfExists('tournament_competation_template');
        Schema::dropIfExists('tournament_contact');
        Schema::dropIfExists('tournament_pricings');
        Schema::dropIfExists('tournament_sponsors');
        Schema::dropIfExists('tournament_template');
        Schema::dropIfExists('transaction_histories');
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('venues');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
