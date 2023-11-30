<?php

use Illuminate\Database\Migrations\Migration;

class AlterPagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE pages CHANGE url url VARCHAR(255) NULL');
        DB::statement('ALTER TABLE pages CHANGE accessible_routes accessible_routes TEXT NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE pages CHANGE url url VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE pages CHANGE accessible_routes accessible_routes TEXT NOT NULL');
    }
}
