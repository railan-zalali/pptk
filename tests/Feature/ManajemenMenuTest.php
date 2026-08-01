<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Test semua route Manajemen dapat diakses oleh manajemen dan admin_pptk.
 */
class ManajemenMenuTest extends TestCase
{
    use RefreshDatabase;

    protected User $manajemen;

    protected function setUp(): void
    {
        parent::setUp();

        $this->manajemen = User::factory()->create([
            'role'              => 'manajemen',
            'email_verified_at' => now(),
        ]);
    }

    // ----------------------------------------------------------------
    // Guest → redirect ke login
    // ----------------------------------------------------------------

    public function test_guest_redirected_to_login(): void
    {
        $response = $this->get(route('manajemen.dashboard'));

        $response->assertRedirect(route('login'));
    }

    // ----------------------------------------------------------------
    // manajemen — semua menu utama panel manajemen
    // ----------------------------------------------------------------

    public function test_manajemen_dashboard_accessible(): void
    {
        $response = $this->actingAs($this->manajemen)->get(route('manajemen.dashboard'));

        $response->assertStatus(200);
    }

    public function test_manajemen_programs_index_accessible(): void
    {
        $response = $this->actingAs($this->manajemen)->get(route('manajemen.programs.index'));

        $response->assertStatus(200);
    }

    public function test_manajemen_strategic_actions_index_accessible(): void
    {
        $response = $this->actingAs($this->manajemen)->get(route('manajemen.strategic-actions.index'));

        $response->assertStatus(200);
    }

    public function test_manajemen_visits_index_accessible(): void
    {
        $response = $this->actingAs($this->manajemen)->get(route('manajemen.visits.index'));

        $response->assertStatus(200);
    }

    public function test_manajemen_penelitian_index_accessible(): void
    {
        $response = $this->actingAs($this->manajemen)->get(route('manajemen.penelitian.index'));

        $response->assertStatus(200);
    }

    public function test_manajemen_community_services_index_accessible(): void
    {
        $response = $this->actingAs($this->manajemen)->get(route('manajemen.community-services.index'));

        $response->assertStatus(200);
    }

    public function test_manajemen_research_budgets_index_accessible(): void
    {
        $response = $this->actingAs($this->manajemen)->get(route('manajemen.research-budgets.index'));

        $response->assertStatus(200);
    }

    // ----------------------------------------------------------------
    // Public dashboard — dapat diakses manajemen
    // ----------------------------------------------------------------

    public function test_public_dashboard_garden_accessible_by_manajemen(): void
    {
        $response = $this->actingAs($this->manajemen)->get(route('dashboard.garden'));

        $response->assertStatus(200);
    }

    // ----------------------------------------------------------------
    // About — dapat diakses manajemen
    // ----------------------------------------------------------------

    public function test_about_page_accessible_by_manajemen(): void
    {
        $response = $this->actingAs($this->manajemen)->get(route('about'));

        $response->assertStatus(200);
    }

    // ----------------------------------------------------------------
    // admin_pptk juga bisa masuk manajemen area
    // ----------------------------------------------------------------

    public function test_admin_pptk_can_access_manajemen_panel(): void
    {
        $admin = User::factory()->create([
            'role'              => 'admin_pptk',
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($admin)->get(route('manajemen.dashboard'));

        $response->assertStatus(200);
    }

    // ----------------------------------------------------------------
    // Sidebar content
    // ----------------------------------------------------------------

    public function test_manajemen_sidebar_shows_main_menu_items(): void
    {
        $response = $this->actingAs($this->manajemen)->get(route('manajemen.dashboard'));

        $response->assertSee('Dashboard Publik');
        $response->assertSee('Program');
        $response->assertSee('Strategic Action');
        $response->assertSee('Kunjungan');
        $response->assertSee('Data Penelitian');
        $response->assertSee('Data Pengabdian Masyarakat');
        $response->assertSee('Tentang');
        $response->assertSee('Realisasi Anggaran');
    }

    public function test_manajemen_sidebar_does_not_show_insight_menu(): void
    {
        // Insight menu sudah disembunyikan dari sidebar manajemen
        $response = $this->actingAs($this->manajemen)->get(route('manajemen.dashboard'));

        $response->assertDontSee('Insight');
    }
}
