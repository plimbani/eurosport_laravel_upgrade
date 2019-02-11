<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGraphicImageToTournamentTemplateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
    */
    public function up()
    {
        Schema::table('tournament_template', function (Blueprint $table) {
            $table->string('graphic_image')->nullable()->default(NULL)->after('json_data');
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
            $table->dropColumn('graphic_image');
        });
    }
}
