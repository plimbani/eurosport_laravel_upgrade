<?php

use Illuminate\Database\Migrations\Migration;

class AlterTextToLongtext extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE tournament_competation_template CHANGE COLUMN template_json_data template_json_data LONGTEXT;');
        DB::statement('ALTER TABLE tournament_template CHANGE COLUMN json_data json_data LONGTEXT NOT NULL;');
        DB::statement('ALTER TABLE tournament_template CHANGE COLUMN template_form_detail template_form_detail LONGTEXT NOT NULL;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE tournament_competation_template CHANGE COLUMN template_json_data template_json_data TEXT;');
        DB::statement('ALTER TABLE tournament_template CHANGE COLUMN json_data json_data TEXT NOT NULL;');
        DB::statement('ALTER TABLE tournament_template CHANGE COLUMN template_form_detail template_form_detail TEXT NOT NULL;');
    }
}
