<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roleAdmin = Role::create(['name' => 'admin']);
        $roleUser = Role::create(['name' => 'user']);
            
        $permissions = [
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',
            'timesheet-list',
            'timesheet-create',
            'timesheet-edit',
            'timesheet-delete'
            ];
    
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        $roleAdmin->givePermissionTo(Permission::all());
        
        // give user role permission
        $roleUser->givePermissionTo(['timesheet-list']);
    }
}
