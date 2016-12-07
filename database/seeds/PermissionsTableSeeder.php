<?php

use Illuminate\Database\Seeder;
use Duro85\Roles\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $routeCollection = Route::getRoutes();
        $createUsersPermission = Permission::create([
        'name' => 'Create users',
        'slug' => 'create.users',
        'description' => '', // optional
    ]);

    $deleteUsersPermission = Permission::create([
        'name' => 'Delete users',
        'slug' => 'delete.users',
    ]);

       /* $i=0;    
        foreach ($routeCollection as $value) {
            if ($value->getName() !== '') {
                $name = title_case(str_replace('.', ' ', $value->getName()));
                if(trim($name) != '') {
                Permission::create([
                    'name' => $name,
                    'slug' => $value->getName(),
                    'description' => $name, // optional                    
                ]);
                }
                $i++;
            }
        } */
    }
}
