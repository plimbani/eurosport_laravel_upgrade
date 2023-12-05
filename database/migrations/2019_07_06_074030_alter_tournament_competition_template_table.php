<?php

use Illuminate\Database\Migrations\Migration;

class AlterTournamentCompetitionTemplateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::statement('ALTER TABLE tournament_competation_template MODIFY COLUMN tournament_template_id INT UNSIGNED NULL DEFAULT NULL;');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::statement('ALTER TABLE tournament_competation_template ALTER COLUMN tournament_template_id DROP DEFAULT');
        DB::statement('ALTER TABLE tournament_competation_template MODIFY COLUMN tournament_template_id INT UNSIGNED NOT NULL');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
