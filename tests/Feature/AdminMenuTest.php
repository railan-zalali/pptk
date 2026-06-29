<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Test akses menu Admin PPTK.
 *
 * Menu Admin (setelah restrukturisasi):
 *   Dashboard Admin, Wilayah Kebun, Kebun, Afdeling/Updeling, Blok,
 *   Realisasi Produksi, Target Kinerja, Realisasi Anggaran, Manajemen Pengguna.
 */
class AdminMenuTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\DatabaseSeeder::class);
        $this->admin = User::where('role', 'admin_pptk')->firstOrFail();
    }

    /** @test */
    public function test_dashboard_admin_dapat_diakses(): void
    {
        $this->actingAs($this->admin)
            ->get(route('admin.dashboard'))
            ->assertStatus(200);
    }

    /** @test */
    public function test_menu_wilayah_kebun_dapat_diakses(): void
    {
        $this->actingAs($this->admin)
            ->get(route('admin.regions.index'))
            ->assertStatus(200);
    }

    /** @test */
    public function test_menu_kebun_dapat_diakses(): void
    {
        $this->actingAs($this->admin)
            ->get(route('admin.gardens.index'))
            ->assertStatus(200);
    }

    /** @test */
    public function test_menu_afdeling_updeling_dapat_diakses(): void
    {
        $this->actingAs($this->admin)
            ->get(route('admin.afdelings.index'))
            ->assertStatus(200);
    }

    /** @test */
    public function test_menu_blok_dapat_diakses(): void
    {
        $this->actingAs($this->admin)
            ->get(route('admin.blocks.index'))
            ->assertStatus(200);
    }

    /** @test */
    public function test_menu_realisasi_produksi_dapat_diakses(): void
    {
        $this->actingAs($this->admin)
            ->get(route('admin.production-realizations.index'))
            ->assertStatus(200);
    }

    /** @test */
    public function test_menu_target_kinerja_dapat_diakses(): void
    {
        $this->actingAs($this->admin)
            ->get(route('admin.performance-targets.index'))
            ->assertStatus(200);
    }

    /** @test */
    public function test_menu_realisasi_anggaran_dapat_diakses(): void
    {
        $this->actingAs($this->admin)
            ->get(route('admin.research-budgets.index'))
            ->assertStatus(200);
    }

    /** @test */
    public function test_menu_manajemen_pengguna_dapat_diakses(): void
    {
        $this->actingAs($this->admin)
            ->get(route('admin.users.index'))
            ->assertStatus(200);
    }

    /** @test */
    public function test_user_belum_login_diarahkan_ke_login_dari_admin(): void
    {
        $this->get(route('admin.dashboard'))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function test_sidebar_admin_tidak_mengandung_link_insight_manajemen(): void
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.dashboard'));

        // Menu insight (dari manajemen) tidak boleh ada di sidebar admin
        $response->assertDontSee(route('manajemen.insights.index'));
    }
}
