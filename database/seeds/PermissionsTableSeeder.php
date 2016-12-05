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
        foreach ($routeCollection as $value) {
            if ($value->getName() !== '') {
                Permission::create([
                    'name' => title_case(str_replace('.', ' ', $value->getName())),
                    'slug' => $value->getName(),
                    'description' => title_case(str_replace('.', ' ', $value->getName())), // optional
                ]);
            }
        }
    }
}
