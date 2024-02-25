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

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = $this->createAdminUser();
        Auth::login($this->admin);
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
        $response = $this->get(route('users.index'));

        $response->assertStatus(200);
        $response->assertViewIs('users.index');
    }

    public function testCreate()
    {
        $response = $this->get(route('users.create'));
        $response->assertStatus(200);
        $response->assertViewIs('users.create');
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

        $response = $this->post(route('users.store'), $data);

        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
    }

    public function testShow()
    {
        $user = factory(User::class)->create();

        $response = $this->get(route('users.show', $user->id));

        $response->assertStatus(200);
        $response->assertViewIs('users.show');
        $response->assertViewHas('user', $user);
    }

    public function testEdit()
    {
        $user = factory(User::class)->create();

        $response = $this->get(route('users.edit', $user->id));

        $response->assertStatus(200);
        $response->assertViewIs('users.edit');
        $response->assertViewHas('user', $user);
    }

    public function testUpdate()
    {
        $user = factory(User::class)->create();
        $data = [
            'name' => 'Updated User',
            'email' => 'updated@example.com',
            'roles' => ['admin']
        ];

        $response = $this->put(route('users.update', $user->id), $data);
        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseHas('users', ['email' => 'updated@example.com']);
    }

    public function testDestroy()
    {
        $user = factory(User::class)->create();

        $response = $this->delete(route('users.destroy', $user->id));

        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}
