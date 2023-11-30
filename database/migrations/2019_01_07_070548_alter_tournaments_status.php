<?php

use Illuminate\Database\Migrations\Migration;

class AlterTournamentsStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE tournaments CHANGE status status ENUM('Published','Unpublished','Closed','Preview')");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE tournaments CHANGE status status ENUM('Published','Unpublished','Closed')");
    }
}
