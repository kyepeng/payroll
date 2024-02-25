<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;
use App\Timesheet;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class TimesheetTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = factory(User::class)->create();
        $this->role = Role::create(['name' => 'admin']);
        $permissions = [
            'timesheet-list',
            'timesheet-create',
            'timesheet-edit',
            'timesheet-delete'
        ];
        foreach ($permissions as $permission) {
            $this->permission[] = Permission::firstOrCreate(['name' => $permission]);
        }
        $this->role->givePermissionTo($this->permission);
        $this->user->assignRole($this->role);

        $this->unauthorizedUser = factory(User::class)->create();
    }

    public function testIndex()
    {
        $response = $this->actingAs($this->user)->get(route('timesheets.index'));
        $response->assertStatus(200);
        $response->assertViewIs('timesheets.index');
    }

    public function testIndexUnauthorized()
    {
        $response = $this->actingAs($this->unauthorizedUser)->get(route('timesheets.index'));
        $response->assertStatus(403);
    }

    public function testCreate()
    {
        $response = $this->actingAs($this->user)->get(route('timesheets.create'));
        $response->assertStatus(200);
        $response->assertViewIs('timesheets.create');
    }

    public function testCreateUnauthorized()
    {
        $response = $this->actingAs($this->unauthorizedUser)->get(route('timesheets.create'));
        $response->assertStatus(403);
    }

    public function testStore()
    {
        $data = [
            'user_id' => $this->user->id,
            'date' => now(),
            'hours_worked' => 1,
            'description' => 'Description'
        ];

        $response = $this->actingAs($this->user)->post(route('timesheets.store'), $data);
        $response->assertRedirect(route('timesheets.index'));
        $this->assertDatabaseHas('timesheets', $data);
    }

    public function testStoreUnauthorized()
    {
        $data = [
            'user_id' => $this->user->id,
            'date' => now(),
            'hours_worked' => 1,
            'description' => 'Description'
        ];
        $response = $this->actingAs($this->unauthorizedUser)->post(route('timesheets.store'), $data);
        $response->assertStatus(403);
    }

    public function testShow()
    {
        $timesheet = factory(Timesheet::class)->create();
        $response = $this->actingAs($this->user)->get(route('timesheets.show', $timesheet->id));
        $response->assertStatus(200);
        $response->assertViewIs('timesheets.show');
        $response->assertViewHas('timesheet', $timesheet);
    }

    public function testShowUnauthorized()
    {
        $timesheet = factory(Timesheet::class)->create();
        $response = $this->actingAs($this->unauthorizedUser)->get(route('timesheets.show', $timesheet->id));
        $response->assertStatus(403);
    }

    public function testEdit()
    {
        $timesheet = factory(Timesheet::class)->create();
        $response = $this->actingAs($this->user)->get(route('timesheets.edit', $timesheet->id));
        $response->assertStatus(200);
        $response->assertViewIs('timesheets.edit');
        $response->assertViewHas('timesheet', $timesheet);
    }

    public function testEditUnauthorized()
    {
        $timesheet = factory(Timesheet::class)->create();
        $response = $this->actingAs($this->unauthorizedUser)->get(route('timesheets.edit', $timesheet->id));
        $response->assertStatus(403);
    }

    public function testUpdate()
    {
        $timesheet = factory(Timesheet::class)->create();
        $data = [
            'user_id' => $this->user->id,
            'date' => now(),
            'hours_worked' => 2,
            'description' => 'Updated Description'
        ];

        $response = $this->actingAs($this->user)->put(route('timesheets.update', $timesheet->id), $data);
        $response->assertRedirect(route('timesheets.index'));
        $this->assertDatabaseHas('timesheets', $data);
    }

    public function testUpdateUnauthorized()
    {
        $timesheet = factory(Timesheet::class)->create();
        $data = [
            'user_id' => $this->user->id,
            'date' => now(),
            'hours_worked' => 2,
            'description' => 'Updated Description'
        ];

        $response = $this->actingAs($this->unauthorizedUser)->put(route('timesheets.update', $timesheet->id), $data);
        $response->assertStatus(403);
    }

    public function testDestroy()
    {
        $timesheet = factory(Timesheet::class)->create();

        $response = $this->actingAs($this->user)->delete(route('timesheets.destroy', $timesheet->id));

        $response->assertRedirect(route('timesheets.index'));
        $this->assertDatabaseMissing('timesheets', ['id' => $timesheet->id]);
    }

    public function testDestroyUnauthorized()
    {
        $timesheet = factory(Timesheet::class)->create();

        $response = $this->actingAs($this->unauthorizedUser)->delete(route('timesheets.destroy', $timesheet->id));
        $response->assertStatus(403);
    }
}
