<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddManualOrderFieldToMatchStandingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('match_standing', function($table) {
           $table->integer('manual_order')->nullable()->after('goal_against');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('match_standing', function($table) {
            $table->dropColumn('manual_order');
        });
    }
}
