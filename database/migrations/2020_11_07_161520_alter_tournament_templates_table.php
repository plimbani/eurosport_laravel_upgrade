<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTournamentTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE tournament_template MODIFY COLUMN minimum_matches VARCHAR(10) NULL DEFAULT NULL;');
        DB::statement("ALTER TABLE tournament_template CHANGE editor_type editor_type ENUM('advance','festival', 'knockout') NOT NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE tournament_template MODIFY COLUMN minimum_matches VARCHAR(10) NOT NULL;');
        DB::statement("ALTER TABLE tournament_template CHANGE editor_type editor_type ENUM('advance','festival')");
    }
}
