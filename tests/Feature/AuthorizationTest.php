<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Test otorisasi berbasis role (role-based access control).
 *
 * Middleware saat ini:
 *   - admin_pptk middleware: mengizinkan admin_pptk DAN manajemen
 *   - manajemen middleware : mengizinkan manajemen DAN admin_pptk
 *
 * Artinya: kedua role bisa akses kedua area, namun tampilan menu berbeda.
 */
class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $manager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\UserSeeder::class);
        $this->admin   = User::where('role', 'admin_pptk')->firstOrFail();
        $this->manager = User::where('role', 'manajemen')->firstOrFail();
    }

    // ─── Redirect sesuai role ─────────────────────────────────────────────

    /** @test */
    public function test_user_belum_login_diarahkan_ke_halaman_login(): void
    {
        $this->get(route('dashboard'))->assertRedirect(route('login'));
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
        $this->get(route('manajemen.dashboard'))->assertRedirect(route('login'));
    }

    /** @test */
    public function test_admin_pptk_diarahkan_ke_dashboard_admin_setelah_login(): void
    {
        $this->actingAs($this->admin)
            ->get(route('dashboard'))
            ->assertRedirect(route('admin.dashboard'));
    }

    /** @test */
    public function test_manajemen_diarahkan_ke_dashboard_manajemen_setelah_login(): void
    {
        $this->actingAs($this->manager)
            ->get(route('dashboard'))
            ->assertRedirect(route('manajemen.dashboard'));
    }

    // ─── Admin PPTK access ────────────────────────────────────────────────

    /** @test */
    public function test_admin_pptk_dapat_akses_area_admin(): void
    {
        $this->actingAs($this->admin)
            ->get(route('admin.dashboard'))
            ->assertStatus(200);
    }

    /** @test */
    public function test_admin_pptk_dapat_akses_area_manajemen(): void
    {
        // Middleware manajemen mengizinkan admin_pptk sebagai superuser
        $this->actingAs($this->admin)
            ->get(route('manajemen.dashboard'))
            ->assertStatus(200);
    }

    // ─── Manajemen access ─────────────────────────────────────────────────

    /** @test */
    public function test_manajemen_dapat_akses_area_manajemen(): void
    {
        $this->actingAs($this->manager)
            ->get(route('manajemen.dashboard'))
            ->assertStatus(200);
    }

    /** @test */
    public function test_manajemen_dapat_akses_area_admin(): void
    {
        // Middleware admin mengizinkan role manajemen (view-only context)
        $this->actingAs($this->manager)
            ->get(route('admin.dashboard'))
            ->assertStatus(200);
    }

    // ─── Profile access ───────────────────────────────────────────────────

    /** @test */
    public function test_admin_dapat_akses_halaman_profil(): void
    {
        $this->actingAs($this->admin)
            ->get(route('profile.edit'))
            ->assertStatus(200);
    }

    /** @test */
    public function test_manajemen_dapat_akses_halaman_profil(): void
    {
        $this->actingAs($this->manager)
            ->get(route('profile.edit'))
            ->assertStatus(200);
    }
}
