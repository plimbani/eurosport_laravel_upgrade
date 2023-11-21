<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsAllAgeCategoriesSelectedToRefereeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('referee', function (Blueprint $table) {
            $table->boolean('is_all_age_categories_selected')->after('comments')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('referee', function (Blueprint $table) {
            $table->dropColumn('is_all_age_categories_selected');
        });
    }
}
