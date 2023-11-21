<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AddDuplicatedFromFieldIntoPitchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pitches', function ($table) {
            $table->unsignedInteger('duplicated_from')->nullable()->default(null)->after('order');
            $table->foreign('duplicated_from')->references('id')->on('pitches')->onDelete(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pitches', function ($table) {
            $table->dropForeign('pitches_duplicated_from_foreign');
            $table->dropColumn('duplicated_from');
        });
    }
}
