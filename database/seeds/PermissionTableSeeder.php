<?php

use Illuminate\Database\Seeder;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',
            'user-list',
            'user-create',
            'user-edit',
            'user-delete',
            'region-list',
            'region-create',
            'region-edit',
            'region-delete'
        ];


        foreach ($permissions as $permission) {
            \App\Permission::create(['name' => $permission]);
        }
    }
}
