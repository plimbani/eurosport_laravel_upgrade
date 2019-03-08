<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewFieldToCompetitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('competitions', function (Blueprint $table) {
            $table->integer('age_category_division_id')->unsigned()->nullable()->default(null)->after('tournament_id');
            $table->foreign('age_category_division_id')->references('id')->on('age_category_divisions')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('competitions', function (Blueprint $table) {
            $table->dropForeign('competitions_age_category_division_id_foreign');
            $table->dropColumn(['age_category_division_id']);
        });
    }
}
