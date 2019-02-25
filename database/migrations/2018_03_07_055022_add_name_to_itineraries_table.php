<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNameToItinerariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('itineraries', function($table) {
            $table->dropColumn('day');
            $table->dropColumn('time');
            $table->dropColumn('item');
            $table->string('name')->after('website_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('itineraries', function($table) {
            $table->dropColumn('name');
            $table->string('item')->after('website_id');
            $table->string('time')->after('website_id');
            $table->string('day')->after('website_id');
        });
    }
}
