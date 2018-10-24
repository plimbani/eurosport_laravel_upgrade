<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewFieldsToTournamentTemplateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tournament_template', function (Blueprint $table) {
            $table->string('avg_matches')->nullable()->after('position_type');
            $table->string('total_matches')->nullable()->after('avg_matches');
            $table->string('divisions')->nullable()->after('total_matches');
            $table->string('version')->nullable()->after('divisions');
            $table->string('created_by')->nullable()->after('version');
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
            $table->dropColumn(['avg_matches', 'total_matches', 'divisions', 'version', 'created_by']);
        });
    }
}
