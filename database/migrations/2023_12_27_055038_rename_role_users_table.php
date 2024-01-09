<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

class RenameRoleUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       /* Schema::disableForeignKeyConstraints();
        DB::table('permissions')->truncate();
        DB::table('permissions')->insert([
            ['name' => 'Create users','guard_name' => 'web'],
            ['name' => 'Delete users','guard_name' => 'web'],
            ['name' => 'Update users','guard_name' => 'web'],
        ]);


        DB::table('roles')->truncate();
        DB::table('roles')->insert([
            ['name1' => 'Super administrator','guard_name' => 'web', 'slug' => 'Super.administrator', 'name' => 'Super.administrator', 'description' => 'The magician', 'level' => '1'],
            ['name1' => 'Tournament administrator','guard_name' => 'web', 'slug' => 'tournament.administrator', 'name' => 'tournament.administrator', 'description' => 'The GOD', 'level' => '1'],
            ['name1' => 'Internal administrator','guard_name' => 'web', 'slug' => 'Internal.administrator', 'name' => 'Internal.administrator','description' => 'The Demigod', 'level' => '1'],
            ['name1' => 'Master administrator','guard_name' => 'web', 'slug' => 'Master.administrator','name' => 'Master.administrator', 'description' => 'The Man', 'level' => '1',],
            ['name1' => 'Mobile user','guard_name' => 'web', 'slug' => 'mobile.user','name' => 'mobile.user', 'description' => 'Mobile User', 'level' => '1',],
            ['name1' => 'Results administrator','guard_name' => 'web', 'slug' => 'Results.administrator','name' => 'Results.administrator', 'description' => 'Results administrator', 'level' => '1'],
        ]);
        Schema::enableForeignKeyConstraints();*/

        $users=User::get();   
        foreach ($users as $user) {
            $role= App\Models\Role::find($user->role);
            $user->assignRole($role->name);
           }
      
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
