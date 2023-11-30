<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->integer('version')->unsigned()->default(1)->after('divisions');
            $table->boolean('is_latest')->default(1)->after('version');
            $table->integer('inherited_from')->unsigned()->nullable()->default(null)->after('is_latest');
            $table->foreign('inherited_from')->references('id')->on('tournament_template')->onDelete('set null')->onUpdate('cascade');
            $table->enum('editor_type', ['advance', 'festival'])->after('inherited_from');
            $table->text('template_form_detail')->after('editor_type');
            $table->integer('created_by')->unsigned()->nullable()->default(null)->after('template_form_detail');
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
            $table->dropForeign('tournament_template_inherited_from_foreign');
            $table->dropColumn(['avg_matches', 'total_matches', 'divisions', 'version', 'is_latest', 'inherited_from', 'editor_type', 'template_form_detail', 'created_by']);
        });
    }
}
