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
            'name' => 'User',
            'email' => 'user@example.com',
            'password' => bcrypt('user'),
        ]);

        // Create an admin user
        $admin = factory(User::class)->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('admin'),
        ]);

        $role = Role::create(['name' => 'Admin']);

        $permissions = Permission::pluck('id','id')->all();
  
        $role->syncPermissions($permissions);
   
        $admin->assignRole([$role->id]);
    }
}