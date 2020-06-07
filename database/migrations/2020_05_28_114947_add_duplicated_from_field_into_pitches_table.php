<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDuplicatedFromFieldIntoPitchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pitches', function($table) {
            $table->unsignedInteger('duplicated_from')->nullable()->default(NULL)->after('order');
            $table->foreign('duplicated_from')->references('id')->on('pitches')->onDelete(NULL);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pitches', function($table) {
            $table->dropForeign('pitches_duplicated_from_foreign');
            $table->dropColumn('duplicated_from');
        });
    }
}
