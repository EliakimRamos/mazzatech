<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

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
            'doctor-list',
            'doctor-create',
            'doctor-edit',
            'doctor-delete',
            'patient-list',
            'patient-create',
            'patient-edit',
            'patient-delete',
            'schedule-list',
            'schedule-create',
            'schedule-edit',
            'schedule-delete',

         ];

         foreach ($permissions as $permission) {
              Permission::create(['name' => $permission]);
         }
    }
}
