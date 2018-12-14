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
            $table->enum('editor_type', ['advance', 'festival'])->after('version');
            $table->text('template_form_detail')->after('editor_type');
            $table->integer('created_by')->unsigned()->nullable()->after('template_form_detail');
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
            $table->dropForeign('tournament_template_created_by_foreign');
            $table->dropColumn(['avg_matches', 'total_matches', 'divisions', 'version', 'editor_type', 'template_form_detail', 'created_by']);
        });
    }
}
