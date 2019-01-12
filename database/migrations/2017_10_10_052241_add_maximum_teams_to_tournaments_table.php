<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMaximumTeamsToTournamentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tournaments', function($table) {
            $table->integer('maximum_teams')->after('name')->nullable();
            $table->string('access_code')->after('maximum_teams')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tournaments', function($table) {
            $table->dropColumn(['maximum_teams', 'access_code']);
        });
    }
}
