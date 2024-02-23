<?php

use Illuminate\Database\Seeder;
use App\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        // Create a normal user
        $user = factory(User::class)->create([
            'name' => 'Users',
            'email' => 'user@payroll.com',
            'password' => bcrypt('user'),
        ]);

        // Create an admin user
        $admin = factory(User::class)->create([
            'name' => 'Admin',
            'email' => 'admin@payroll.com',
            'password' => bcrypt('admin'),
        ]);
        
        // assign admin
        $permissions = Permission::pluck('id','id')->all();
        $this->createRoleAndAssign($admin, 'admin', $permissions);

        // assign user
        $permissions = Permission::pluck('id','id')
        ->where('name', 'like', '%timesheet%')
        ->all();
        $this->createRoleAndAssign($user, 'user', $permissions);
    }

    public function createRoleAndAssign(User $user, string $role, array $permissions) {
        $role = Role::firstOrCreate(['name' => $role]);
        $role->syncPermissions($permissions);
        $user->assignRole([$role->id]);
    }
}