<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Test semua route Admin PPTK dapat diakses oleh admin_pptk.
 * Test bahwa manajemen tidak bisa masuk.
 */
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

    // ----------------------------------------------------------------
    // Guest → redirect ke login
    // ----------------------------------------------------------------

    public function test_guest_redirected_to_login(): void
    {
        $response = $this->get(route('admin.dashboard'));

        $response->assertRedirect(route('login'));
    }

    // ----------------------------------------------------------------
    // admin_pptk — semua menu master kebun
    // ----------------------------------------------------------------

    public function test_admin_dashboard_accessible(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
    }

    public function test_admin_regions_index_accessible(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.regions.index'));

        $response->assertStatus(200);
    }

    public function test_admin_gardens_index_accessible(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.gardens.index'));

        $response->assertStatus(200);
    }

    public function test_admin_afdelings_index_accessible(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.afdelings.index'));

        $response->assertStatus(200);
    }

    public function test_admin_blocks_index_accessible(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.blocks.index'));

        $response->assertStatus(200);
    }

    // ----------------------------------------------------------------
    // Produksi & Target
    // ----------------------------------------------------------------

    public function test_admin_production_realizations_index_accessible(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.production-realizations.index'));

        $response->assertStatus(200);
    }

    public function test_admin_performance_targets_index_accessible(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.performance-targets.index'));

        $response->assertStatus(200);
    }

    // ----------------------------------------------------------------
    // Manajemen Pengguna
    // ----------------------------------------------------------------

    public function test_admin_users_index_accessible(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.users.index'));

        $response->assertStatus(200);
    }

    // ----------------------------------------------------------------
    // Endpoint pendukung (Strategic Actions, Insights, dll.)
    // ----------------------------------------------------------------

    public function test_admin_strategic_actions_index_accessible(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.strategic-actions.index'));

        $response->assertStatus(200);
    }

    public function test_admin_programs_index_accessible(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.programs.index'));

        $response->assertStatus(200);
    }

    public function test_admin_insights_index_accessible(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.insights.index'));

        $response->assertStatus(200);
    }

    public function test_admin_visits_index_accessible(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.visits.index'));

        $response->assertStatus(200);
    }

    // ----------------------------------------------------------------
    // Sidebar content — menu utama terlihat
    // ----------------------------------------------------------------

    public function test_admin_sidebar_shows_main_menu_items(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));

        $response->assertSee('Wilayah Kebun');
        $response->assertSee('Kebun');
        $response->assertSee('Afdeling');
        $response->assertSee('Blok');
        $response->assertSee('Realisasi Produksi');
        $response->assertSee('Target Kinerja');
        $response->assertSee('Manajemen Pengguna');
    }

    // ----------------------------------------------------------------
    // manajemen tidak bisa masuk admin area
    // ----------------------------------------------------------------

    public function test_manajemen_redirected_from_admin_panel(): void
    {
        $manajemen = User::factory()->create([
            'role'              => 'manajemen',
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($manajemen)->get(route('admin.dashboard'));

        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('error');
    }
}
