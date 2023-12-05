<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AddTeamIdInUsersFavouriteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users_favourite', function ($table) {
            $table->integer('team_id')->default(0)->unsigned(10)->after('tournament_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users_favourite', function ($table) {
            $table->dropColumn('team_id');
        });
    }
}
