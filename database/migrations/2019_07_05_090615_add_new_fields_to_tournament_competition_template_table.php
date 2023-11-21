<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->text('template_json_data')->nullable()->after('group_size');
            $table->string('template_font_color')->nullable()->after('template_json_data');
            $table->string('remarks')->nullable()->after('template_font_color');
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
            $table->dropColumn(['tournament_format', 'competition_type', 'group_size', 'template_json_data', 'template_font_color', 'remarks']);
        });
    }
}
