<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDeviceVersionToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('device')->nullable()->default(NULL)->after('country_id');
            $table->string('app_version')->nullable()->default(NULL)->after('device');
            $table->string('os_version')->nullable()->default(NULL)->after('app_version');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('device');
            $table->dropColumn('app_version');
            $table->dropColumn('os_version');
        });
    }
}
