<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAgeCategoryDivisionIdToCompetitionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('competitions', function (Blueprint $table) {
            $table->integer('age_category_division_id')->after('tournament_id')->nullable()->unsigned()->index();
            $table->foreign('age_category_division_id')->references('id')->on('age_category_divisions');
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
            $table->dropColumn('age_category_division_id');
        });
    }
}
