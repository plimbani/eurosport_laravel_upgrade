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
            $table->enum('editor_type', ['advance', 'simple'])->after('version');
            $table->enum('competition_type', ['league', 'knockout'])->nullable()->after('editor_type');
            $table->integer('created_by')->unsigned()->nullable()->after('competition_type');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
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
            $table->dropColumn(['avg_matches', 'total_matches', 'divisions', 'version', 'editor_type', 'competition_type', 'created_by']);
        });
    }
}
