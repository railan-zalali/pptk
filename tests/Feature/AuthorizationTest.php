<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Test otorisasi berbasis role:
 * - Guest → redirect ke login
 * - admin_pptk → akses admin + manajemen panel
 * - manajemen → akses manajemen panel saja
 * - Middleware error session
 */
class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    // ----------------------------------------------------------------
    // Guest → harus redirect ke login
    // ----------------------------------------------------------------

    public function test_guest_cannot_access_authenticated_dashboard(): void
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect(route('login'));
    }

    public function test_guest_cannot_access_admin_panel(): void
    {
        $response = $this->get(route('admin.dashboard'));

        $response->assertRedirect(route('login'));
    }

    public function test_guest_cannot_access_manajemen_panel(): void
    {
        $response = $this->get(route('manajemen.dashboard'));

        $response->assertRedirect(route('login'));
    }

    // ----------------------------------------------------------------
    // admin_pptk — akses penuh
    // ----------------------------------------------------------------

    public function test_admin_pptk_can_access_admin_panel(): void
    {
        $admin = User::factory()->create([
            'role'              => 'admin_pptk',
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
    }

    public function test_admin_pptk_can_access_manajemen_panel(): void
    {
        // admin_pptk juga diizinkan masuk manajemen panel (IsManajemen middleware)
        $admin = User::factory()->create([
            'role'              => 'admin_pptk',
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($admin)->get(route('manajemen.dashboard'));

        $response->assertStatus(200);
    }

    // ----------------------------------------------------------------
    // manajemen — akses manajemen panel, tidak bisa admin panel
    // ----------------------------------------------------------------

    public function test_manajemen_can_access_manajemen_panel(): void
    {
        $manajemen = User::factory()->create([
            'role'              => 'manajemen',
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($manajemen)->get(route('manajemen.dashboard'));

        $response->assertStatus(200);
    }

    public function test_manajemen_cannot_access_admin_panel(): void
    {
        $manajemen = User::factory()->create([
            'role'              => 'manajemen',
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($manajemen)->get(route('admin.dashboard'));

        // IsAdmin middleware redirect ke dashboard dengan error
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

        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('error');
    }

    public function test_manajemen_cannot_access_admin_users(): void
    {
        $manajemen = User::factory()->create([
            'role'              => 'manajemen',
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($manajemen)->get(route('admin.users.index'));

        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('error');
    }

    // ----------------------------------------------------------------
    // Profile — accessible oleh kedua role
    // ----------------------------------------------------------------

    public function test_admin_can_access_profile(): void
    {
        $admin = User::factory()->create([
            'role'              => 'admin_pptk',
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($admin)->get(route('profile.edit'));

        $response->assertStatus(200);
    }

    public function test_manajemen_can_access_profile(): void
    {
        $manajemen = User::factory()->create([
            'role'              => 'manajemen',
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($manajemen)->get(route('profile.edit'));

        $response->assertStatus(200);
    }

    // ----------------------------------------------------------------
    // Dashboard redirect sesuai role
    // ----------------------------------------------------------------

    public function test_authenticated_user_can_access_dashboard_route(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);

        $response = $this->actingAs($user)->get(route('dashboard'));

        // DashboardController::user() redirect berdasarkan role ke admin atau manajemen panel
        $response->assertRedirect();
    }
}
