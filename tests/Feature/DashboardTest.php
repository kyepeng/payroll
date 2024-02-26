<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;
use App\Timesheet;
use Illuminate\Http\Request;
use Mockery;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\DashboardController;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->role = Role::create(['name' => 'admin']);
        $this->user = factory(User::class)->create();
        $this->user->assignRole($this->role);

        $this->unauthorizedUser = factory(User::class)->create();
    }

    public function testIndex()
    {
        $response = $this->actingAs($this->user)->get(route('dashboards.index'));
        $response->assertStatus(200);
        $response->assertViewIs('dashboards.index');
    }

    public function testIndexUnauthorized()
    {
        $response = $this->actingAs($this->unauthorizedUser)->get(route('dashboards.index'));
        $response->assertStatus(403);
    }
}
