<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteWebsiteTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('contacts');
        Schema::dropIfExists('documents');
        Schema::dropIfExists('history_age_categories');
        Schema::dropIfExists('history_teams');
        Schema::dropIfExists('history_years');
        Schema::dropIfExists('inquiries');
        Schema::dropIfExists('itineraries');
        Schema::dropIfExists('itinerary_items');
        Schema::dropIfExists('locations');
        Schema::dropIfExists('maps');
        Schema::dropIfExists('organisers');
        Schema::dropIfExists('pages');
        Schema::dropIfExists('photos');
        Schema::dropIfExists('sponsors');
        Schema::dropIfExists('statistics');
        Schema::dropIfExists('website_settings');
        Schema::dropIfExists('website_user');
        Schema::dropIfExists('websites');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
