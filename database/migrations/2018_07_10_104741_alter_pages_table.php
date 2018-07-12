<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
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
        DB::statement('ALTER TABLE pages CHANGE page_name page_name VARCHAR(255) NULL');
        DB::statement('ALTER TABLE pages CHANGE name name VARCHAR(255) NULL');
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
        DB::statement('ALTER TABLE pages CHANGE page_name page_name VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE pages CHANGE name name VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE pages CHANGE accessible_routes accessible_routes TEXT NOT NULL');
    }
}
