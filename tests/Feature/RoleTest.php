<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = factory(User::class)->create();
        $this->role = Role::create(['name' => 'admin']);
        $permissions = [
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',
        ];
        foreach ($permissions as $permission) {
            $this->permission[]= Permission::firstOrCreate(['name' => $permission]);
        }
        $this->role->givePermissionTo($this->permission);
        $this->user->assignRole($this->role);

        $this->unauthorizedUser = factory(User::class)->create();
    }

    public function testIndex()
    {
        $response = $this->actingAs($this->user)->get(route('roles.index'));
        $response->assertStatus(200);
        $response->assertViewIs('roles.index');
    }

    public function testIndexUnauthorized()
    {
        $response = $this->actingAs($this->unauthorizedUser)->get(route('roles.index'));
        $response->assertStatus(403);
    }

    public function testCreate()
    {
        $response = $this->actingAs($this->user)->get(route('roles.create'));
        $response->assertStatus(200);
        $response->assertViewIs('roles.create');
    }

    public function testCreateUnauthorized()
    {
        $response = $this->actingAs($this->unauthorizedUser)->get(route('roles.create'));
        $response->assertStatus(403);
    }

    public function testStore()
    {
        $data = [
            'name' => 'test role',
            'permission' => ['name' => 'role-list']
        ];
        $response = $this->actingAs($this->user)->post(route('roles.store'), $data);
        $response->assertRedirect(route('roles.index'));
        $this->assertDatabaseHas('roles', ['name' => 'test role']);
    }

    public function testStoreUnauthorized()
    {
        $data = [
            'name' => 'test role',
            'permission' => ['name' => 'role-list']
        ];
        $response = $this->actingAs($this->unauthorizedUser)->post(route('roles.store'), $data);
        $response->assertStatus(403);
    }

    public function testShow()
    {
        $response = $this->actingAs($this->user)->get(route('roles.show', $this->role->id));
        $response->assertStatus(200);
        $response->assertViewIs('roles.show');
    }

    public function testShowUnauthorized()
    {
        $response = $this->actingAs($this->unauthorizedUser)->get(route('roles.show', $this->role->id));
        $response->assertStatus(403);
    }

    public function testEdit()
    {
        $response = $this->actingAs($this->user)->get(route('roles.edit', $this->role->id));
        $response->assertStatus(200);
        $response->assertViewIs('roles.edit');
    }

    public function testEditUnauthorized()
    {
        $response = $this->actingAs($this->unauthorizedUser)->get(route('roles.edit', $this->role->id));
        $response->assertStatus(403);
    }

    public function testUpdate()
    {
        $data = [
            'name' => 'updated role',
            'permission' => ['name' => 'role-list']
        ];
        $response = $this->actingAs($this->user)->put(route('roles.update', $this->role->id), $data);
        $this->assertDatabaseHas('roles', ['name' => 'updated role']);
        $response->assertRedirect(route('roles.index'));
    }

    public function testUpdateUnauthorized()
    {
        $data = [
            'name' => 'updated role',
            'permission' => ['name' => 'role-list']
        ];
        $response = $this->actingAs($this->unauthorizedUser)->put(route('roles.update', $this->role->id), $data);
        $response->assertStatus(403);
    }

    public function testDestroy()
    {
        $response = $this->actingAs($this->user)->delete(route('roles.destroy',  $this->role->id));
        $response->assertRedirect(route('roles.index'));
        $this->assertDatabaseMissing('roles', ['id' => $this->role->id]);
    }

    public function testDestroyUnauthorized()
    {
        $response = $this->actingAs($this->unauthorizedUser)->delete(route('roles.destroy',  $this->role->id));
        $response->assertStatus(403);
    }
}
