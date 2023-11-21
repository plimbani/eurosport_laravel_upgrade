<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AddCategoryAgeFontColorToTournamentCompetationTemplateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tournament_competation_template', function ($table) {
            $table->string('category_age_font_color', 100)->nullable()->after('category_age_color');
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
            $table->dropColumn('category_age_font_color');
        });
    }
}
