<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameRoleUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('permissions')->truncate();
        DB::table('permissions')->insert([
            ['name' => 'Create users','guard_name' => 'web'],
            ['name' => 'Delete users','guard_name' => 'web'],
            ['name' => 'Update users','guard_name' => 'web'],
        ]);


        DB::table('roles')->truncate();
        DB::table('roles')->insert([
            ['name' => 'Super administrator','guard_name' => 'web', 'slug' => 'Super.administrator', 'description' => 'The magician', 'level' => '1'],
            ['name' => 'Tournament administrator','guard_name' => 'web', 'slug' => 'tournament.administrator', 'description' => 'The GOD', 'level' => '1'],
            ['name' => 'Internal administrator','guard_name' => 'web', 'slug' => 'Internal.administrator', 'description' => 'The Demigod', 'level' => '1'],
            ['name' => 'Master administrator','guard_name' => 'web', 'slug' => 'Master.administrator', 'description' => 'The Man', 'level' => '1',],
            ['name' => 'Mobile user','guard_name' => 'web', 'slug' => 'mobile.user', 'description' => 'Mobile User', 'level' => '1',],
            ['name' => 'Results administrator','guard_name' => 'web', 'slug' => 'Results.administrator', 'description' => 'Results administrator', 'level' => '1'],
        ]);
        Schema::enableForeignKeyConstraints();


      
        }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('users', function(Blueprint $table) {
            $table->renameColumn('sub_role', 'role');
        });
    }
}
