<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminUserTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    public function test_admin_can_view_users_list(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.users.index'));

        $response->assertStatus(200);
        $response->assertSee($this->admin->email);
    }

    public function test_admin_can_create_user(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.users.store'), [
            'name' => 'Manager Baru',
            'email' => 'manager.baru@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'manager',
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', [
            'name' => 'Manager Baru',
            'email' => 'manager.baru@example.com',
            'role' => 'manager',
        ]);
    }

    public function test_admin_can_update_user(): void
    {
        $user = User::factory()->create(['role' => 'viewer']);

        $response = $this->actingAs($this->admin)->put(route('admin.users.update', $user), [
            'name' => 'Viewer Update',
            'email' => 'viewer.update@example.com',
            'role' => 'manager',
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertEquals('Viewer Update', $user->refresh()->name);
        $this->assertEquals('manager', $user->role);
    }

    public function test_admin_can_delete_other_user(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($this->admin)->delete(route('admin.users.destroy', $user));

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function test_admin_cannot_delete_themselves(): void
    {
        $response = $this->actingAs($this->admin)->delete(route('admin.users.destroy', $this->admin));

        $response->assertSessionHas('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        $this->assertDatabaseHas('users', ['id' => $this->admin->id]);
    }
}
