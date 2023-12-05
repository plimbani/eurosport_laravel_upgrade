<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AddIsPublishedPreviewOnceToTournamentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tournaments', function ($table) {
            $table->tinyInteger('is_published_preview_once')->default(0)->after('points_per_bye');
            $table->unsignedInteger('duplicated_from')->nullable()->default(null)->after('points_per_bye');
            $table->foreign('duplicated_from')->references('id')->on('tournaments')->onDelete(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tournaments', function ($table) {
            $table->dropForeign('tournaments_duplicated_from_foreign');
            $table->dropColumn(['is_published_preview_once', 'duplicated_from']);
        });
    }
}
