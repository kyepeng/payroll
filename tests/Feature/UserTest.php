<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Http\Controllers\UserController;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = factory(User::class)->create();
        $this->role = Role::create(['name' => 'admin']);
        $permissions = [
            'user-list',
            'user-create',
            'user-edit',
            'user-delete',
        ];
        foreach ($permissions as $permission) {
            $this->permission[] = Permission::firstOrCreate(['name' => $permission]);
        }
        $this->role->givePermissionTo($this->permission);
        $this->user->assignRole($this->role);

        $this->unauthorizedUser = factory(User::class)->create();
    }

    public function createAdminUser() {
        $admin = factory(User::class)->create([
            'name' => 'Admin',
            'email' => 'admin@payroll.com',
            'password' => bcrypt('admin'),
        ]);
        $role = Role::firstOrCreate(['name' => 'admin']);    
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

        $role->givePermissionTo(Permission::all());
        $permissions = Permission::pluck('id','id')->all();
        $role->syncPermissions($permissions);
        $admin->assignRole([$role->id]);

        return $admin;
    }

    public function testIndex()
    {
        $response = $this->actingAs($this->user)->get(route('users.index'));
        $response->assertStatus(200);
        $response->assertViewIs('users.index');
    }

    public function testIndexUnauthorized()
    {
        $response = $this->actingAs($this->unauthorizedUser)->get(route('users.index'));
        $response->assertStatus(403);
    }

    public function testCreate()
    {
        $response = $this->actingAs($this->user)->get(route('users.create'));
        $response->assertStatus(200);
        $response->assertViewIs('users.create');
    }

    public function testCreateUnauthorized()
    {
        $response = $this->actingAs($this->unauthorizedUser)->get(route('users.create'));
        $response->assertStatus(403);
    }

    public function testStore()
    {
        $data = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'confirm-password' => 'password', 
            'roles' => ['admin']
        ];

        $response = $this->actingAs($this->user)->post(route('users.store'), $data);
        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
    }

    public function testStoreUnauthorized()
    {
        $data = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'confirm-password' => 'password',
            'roles' => ['admin']
        ];
        $response = $this->actingAs($this->unauthorizedUser)->post(route('users.store'), $data);
        $response->assertStatus(403);
    }

    public function testShow()
    {
        $user = factory(User::class)->create();
        $response = $this->actingAs($this->user)->get(route('users.show', $user->id));
        $response->assertStatus(200);
        $response->assertViewIs('users.show');
        $response->assertViewHas('user', $user);
    }

    public function testShowUnauthorized()
    {
        $user = factory(User::class)->create();
        $response = $this->actingAs($this->unauthorizedUser)->get(route('users.show', $user->id));
        $response->assertStatus(403);
    }

    public function testEdit()
    {
        $user = factory(User::class)->create();
        $response = $this->actingAs($this->user)->get(route('users.edit', $user->id));
        $response->assertStatus(200);
        $response->assertViewIs('users.edit');
        $response->assertViewHas('user', $user);
    }

    public function testEditUnauthorized()
    {
        $user = factory(User::class)->create();
        $response = $this->actingAs($this->unauthorizedUser)->get(route('users.edit', $user->id));
        $response->assertStatus(403);
    }

    public function testUpdate()
    {
        $user = factory(User::class)->create();
        $data = [
            'name' => 'Updated User',
            'email' => 'updated@example.com',
            'roles' => ['admin']
        ];

        $response = $this->actingAs($this->user)->put(route('users.update', $user->id), $data);
        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseHas('users', ['email' => 'updated@example.com']);
    }

    public function testUpdateUnauthorized()
    {
        $user = factory(User::class)->create();
        $data = [
            'name' => 'Updated User',
            'email' => 'updated@example.com',
            'roles' => ['admin']
        ];

        $response = $this->actingAs($this->unauthorizedUser)->put(route('users.update', $user->id), $data);
        $response->assertStatus(403);
    }

    public function testDestroy()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($this->user)->delete(route('users.destroy', $user->id));

        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function testDestroyUnauthorized()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($this->unauthorizedUser)->delete(route('users.destroy', $user->id));
        $response->assertStatus(403);
    }
}
