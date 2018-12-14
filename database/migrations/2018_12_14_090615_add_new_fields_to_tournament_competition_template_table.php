<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewFieldsToTournamentCompetitionTemplateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tournament_competation_template', function (Blueprint $table) {
            $table->enum('tournament_format', ['advance', 'festival', 'basic'])->after('total_match');
            $table->enum('competition_type', ['league', 'knockout'])->nullable()->after('tournament_format');
            $table->integer('group_size')->nullable()->unsigned(10)->after('competition_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tournament_competation_template', function (Blueprint $table) {
            $table->dropColumn(['tournament_format', 'competition_type', 'group_size']);
        });
    }
}
