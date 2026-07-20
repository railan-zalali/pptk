<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_redirected_to_login(): void
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_access_admin_area(): void
    {
        $admin = User::factory()->create([
            'role'              => 'admin_pptk',
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
    }

    public function test_admin_can_access_manajemen_area(): void
    {
        $admin = User::factory()->create([
            'role'              => 'admin_pptk',
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($admin)->get(route('manajemen.dashboard'));

        $response->assertStatus(200);
    }

    public function test_manajemen_can_access_manajemen_area(): void
    {
        $manajemen = User::factory()->create([
            'role'              => 'manajemen',
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($manajemen)->get(route('manajemen.dashboard'));

        $response->assertStatus(200);
    }

    public function test_manajemen_cannot_access_admin_area(): void
    {
        $manajemen = User::factory()->create([
            'role'              => 'manajemen',
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($manajemen)->get(route('admin.dashboard'));

        // IsAdmin middleware redirects to dashboard with error message
        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('error');
    }

    public function test_manajemen_cannot_access_admin_regions(): void
    {
        $manajemen = User::factory()->create([
            'role'              => 'manajemen',
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($manajemen)->get(route('admin.regions.index'));

        // IsAdmin middleware redirects to dashboard with error message
        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('error');
    }

    public function test_both_roles_can_access_profile(): void
    {
        $admin = User::factory()->create([
            'role'              => 'admin_pptk',
            'email_verified_at' => now(),
        ]);

        $manajemen = User::factory()->create([
            'role'              => 'manajemen',
            'email_verified_at' => now(),
        ]);

        $response1 = $this->actingAs($admin)->get(route('profile.edit'));
        $response1->assertStatus(200);

        $response2 = $this->actingAs($manajemen)->get(route('profile.edit'));
        $response2->assertStatus(200);
    }

    public function test_admin_dashboard_shows_role_label(): void
    {
        $admin = User::factory()->create([
            'name'  => 'Admin PPTK',
            'role'  => 'admin_pptk',
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertSee('Admin PPTK');
    }

    public function test_manajemen_dashboard_shows_role_label(): void
    {
        $manajemen = User::factory()->create([
            'name'  => 'Manajemen Eksekutif',
            'role'  => 'manajemen',
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($manajemen)->get(route('manajemen.dashboard'));

        $response->assertSee('Manajemen');
    }
}
