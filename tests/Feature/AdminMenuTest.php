<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminMenuTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role'              => 'admin_pptk',
            'email_verified_at' => now(),
        ]);
    }

    public function test_guest_redirected_to_login(): void
    {
        $response = $this->get(route('admin.dashboard'));

        $response->assertRedirect(route('login'));
    }

    public function test_admin_dashboard_dapat_diakses(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
    }

    public function test_menu_regions_dapat_diakses(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.regions.index'));

        $response->assertStatus(200);
    }

    public function test_menu_gardens_dapat_diakses(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.gardens.index'));

        $response->assertStatus(200);
    }

    public function test_menu_afdelings_dapat_diakses(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.afdelings.index'));

        $response->assertStatus(200);
    }

    public function test_menu_blocks_dapat_diakses(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.blocks.index'));

        $response->assertStatus(200);
    }

    public function test_menu_production_realizations_dapat_diakses(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.production-realizations.index'));

        $response->assertStatus(200);
    }

    public function test_menu_performance_targets_dapat_diakses(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.performance-targets.index'));

        $response->assertStatus(200);
    }

    public function test_menu_users_dapat_diakses(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.users.index'));

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

    public function test_admin_sidebar_contains_expected_menu_items(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));

        $response->assertSee('Wilayah Kebun');
        $response->assertSee('Kebun');
        $response->assertSee('Afdeling');
        $response->assertSee('Blok');
        $response->assertSee('Realisasi Produksi');
        $response->assertSee('Target Kinerja');
        $response->assertSee('Manajemen Pengguna');
        $response->assertSee('Halaman Depan');
    }
}
