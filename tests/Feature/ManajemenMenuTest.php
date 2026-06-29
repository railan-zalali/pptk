<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Test akses menu Manajemen.
 *
 * Menu Manajemen (setelah restrukturisasi):
 *   Dashboard, Dashboard Publik, Program, Strategic Action,
 *   Kunjungan, Data Penelitian, Data Pengabdian Masyarakat, Tentang.
 *   Insight: DISEMBUNYIKAN dari menu (route tetap ada).
 */
class ManajemenMenuTest extends TestCase
{
    use RefreshDatabase;

    private User $manager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\DatabaseSeeder::class);
        $this->manager = User::where('role', 'manajemen')->firstOrFail();
    }

    /** @test */
    public function test_dashboard_manajemen_dapat_diakses(): void
    {
        $this->actingAs($this->manager)
            ->get(route('manajemen.dashboard'))
            ->assertStatus(200);
    }

    /** @test */
    public function test_menu_dashboard_publik_dapat_diakses(): void
    {
        $this->actingAs($this->manager)
            ->get(route('dashboard.garden'))
            ->assertStatus(200);
    }

    /** @test */
    public function test_menu_program_dapat_diakses(): void
    {
        $this->actingAs($this->manager)
            ->get(route('manajemen.programs.index'))
            ->assertStatus(200);
    }

    /** @test */
    public function test_menu_strategic_action_dapat_diakses(): void
    {
        $this->actingAs($this->manager)
            ->get(route('manajemen.strategic-actions.index'))
            ->assertStatus(200);
    }

    /** @test */
    public function test_menu_kunjungan_dapat_diakses(): void
    {
        $this->actingAs($this->manager)
            ->get(route('manajemen.visits.index'))
            ->assertStatus(200);
    }

    /** @test */
    public function test_menu_data_penelitian_dapat_diakses(): void
    {
        $this->actingAs($this->manager)
            ->get(route('manajemen.penelitian.index'))
            ->assertStatus(200);
    }

    /** @test */
    public function test_menu_data_pengabdian_masyarakat_dapat_diakses(): void
    {
        $this->actingAs($this->manager)
            ->get(route('manajemen.community-services.index'))
            ->assertStatus(200);
    }

    /** @test */
    public function test_menu_tentang_dapat_diakses(): void
    {
        $this->actingAs($this->manager)
            ->get(route('about'))
            ->assertStatus(200);
    }

    /** @test */
    public function test_insight_tidak_muncul_di_sidebar_manajemen(): void
    {
        $response = $this->actingAs($this->manager)
            ->get(route('manajemen.dashboard'));

        $response->assertStatus(200);

        // Insight harus disembunyikan dari sidebar (link tidak boleh ada)
        $response->assertDontSee('href="' . route('manajemen.insights.index') . '"', false);
    }

    /** @test */
    public function test_sidebar_manajemen_tidak_mengandung_link_master_kebun_admin(): void
    {
        $response = $this->actingAs($this->manager)
            ->get(route('manajemen.dashboard'));

        // Master kebun sekarang hanya di admin, tidak di manajemen
        $response->assertDontSee(route('admin.regions.index'));
        $response->assertDontSee(route('admin.production-realizations.index'));
    }

    /** @test */
    public function test_dashboard_manajemen_tidak_menampilkan_widget_insight(): void
    {
        $response = $this->actingAs($this->manager)
            ->get(route('manajemen.dashboard'));

        // Widget "Insight Terbaru" sudah dihapus dari dashboard
        $response->assertDontSee('Insight Terbaru');
    }

    /** @test */
    public function test_user_belum_login_diarahkan_ke_login_dari_manajemen(): void
    {
        $this->get(route('manajemen.dashboard'))
            ->assertRedirect(route('login'));
    }
}
