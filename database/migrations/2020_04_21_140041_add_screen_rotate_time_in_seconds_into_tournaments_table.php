<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddScreenRotateTimeInSecondsIntoTournamentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tournaments', function($table) {
            $table->integer('screen_rotate_time_in_seconds')->after('is_published_preview_once')->default(10);
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
            $table->dropColumn('screen_rotate_time_in_seconds');
        });
    }
}
