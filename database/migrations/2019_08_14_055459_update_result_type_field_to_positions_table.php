<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateResultTypeFieldToPositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `positions` CHANGE `result_type` `result_type` ENUM('winner','looser', 'loser') NULL DEFAULT NULL;");
        DB::statement("UPDATE `positions` set `result_type` = 'loser' where `result_type` = 'looser';");
        DB::statement("ALTER TABLE `positions` CHANGE `result_type` `result_type` ENUM('winner','loser') NULL DEFAULT NULL;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE `positions` CHANGE `result_type` `result_type` ENUM('winner','loser', 'looser') NULL DEFAULT NULL;");
        DB::statement("UPDATE `positions` set `result_type` = 'looser' where `result_type` = 'loser';");
        DB::statement("ALTER TABLE `positions` CHANGE `result_type` `result_type` ENUM('winner','looser') NULL DEFAULT NULL;");
    }
}
