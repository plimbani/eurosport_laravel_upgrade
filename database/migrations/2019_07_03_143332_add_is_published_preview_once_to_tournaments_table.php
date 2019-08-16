<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsPublishedPreviewOnceToTournamentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tournaments', function($table) {
            $table->tinyInteger('is_published_preview_once')->default(0)->after('points_per_bye');
            $table->unsignedInteger('duplicated_from')->nullable()->default(NULL)->after('points_per_bye');
            $table->foreign('duplicated_from')->references('id')->on('tournaments')->onDelete(NULL);
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
            $table->dropForeign('tournaments_duplicated_from_foreign');
            $table->dropColumn(['is_published_preview_once', 'duplicated_from']);
        });
    }
}
