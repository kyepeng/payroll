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
        $roleAdmin = Role::firstOrCreate(['name' => 'admin']);
        $roleUser = Role::firstOrCreate(['name' => 'user']);
            
        $permissions = [
            'user-list',
            'user-create',
            'user-edit',
            'user-delete',
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
            Permission::firstOrCreate(['name' => $permission]);
        }

        $roleAdmin->givePermissionTo(Permission::all());
        
        // give user role permission
        $roleUser->givePermissionTo([
            'user-list',
            'user-edit',
            'timesheet-list',
            'timesheet-create',
            'timesheet-edit',
            'timesheet-delete'
        ]);
    }
}
