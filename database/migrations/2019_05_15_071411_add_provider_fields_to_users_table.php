<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AddProviderFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function ($table) {
            $table->string('provider')->default('email')->after('os_version');
            $table->string('provider_id')->nullable()->after('provider');
            DB::statement('ALTER TABLE `users` MODIFY `email` VARCHAR(255) NULL;');
            DB::statement('ALTER TABLE `users` MODIFY `username` VARCHAR(255) NULL;');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function ($table) {
            $table->dropColumn(['provider', 'provider_id']);
            DB::statement('ALTER TABLE `users` MODIFY `email` VARCHAR(255) NOT NULL;');
            DB::statement('ALTER TABLE `users` MODIFY `username` VARCHAR(255) NOT NULL;');
        });
    }
}
