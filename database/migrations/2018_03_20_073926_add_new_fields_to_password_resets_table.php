<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AddNewFieldsToPasswordResetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('password_resets', function ($table) {
            $table->string('tries')->after('token')->default(0);
            $table->timestamp('last_requested_at')->after('tries')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('password_resets', function ($table) {
            $table->dropColumn(['tries', 'last_requested_at']);
        });
    }
}
