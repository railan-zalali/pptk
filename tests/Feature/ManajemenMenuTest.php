<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

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

    public function test_guest_redirected_to_login(): void
    {
        $response = $this->get(route('manajemen.dashboard'));

        $response->assertRedirect(route('login'));
    }

    public function test_dashboard_manajemen_dapat_diakses(): void
    {
        $response = $this->actingAs($this->manajemen)->get(route('manajemen.dashboard'));

        $response->assertStatus(200);
    }

    public function test_menu_dashboard_publik_dapat_diakses(): void
    {
        $response = $this->actingAs($this->manajemen)->get(route('dashboard.garden'));

        $response->assertStatus(200);
    }

    public function test_menu_program_dapat_diakses(): void
    {
        $response = $this->actingAs($this->manajemen)->get(route('manajemen.programs.index'));

        $response->assertStatus(200);
    }

    public function test_menu_strategic_action_dapat_diakses(): void
    {
        $response = $this->actingAs($this->manajemen)->get(route('manajemen.strategic-actions.index'));

        $response->assertStatus(200);
    }

    public function test_menu_kunjungan_dapat_diakses(): void
    {
        $response = $this->actingAs($this->manajemen)->get(route('manajemen.visits.index'));

        $response->assertStatus(200);
    }

    public function test_menu_data_penelitian_dapat_diakses(): void
    {
        $response = $this->actingAs($this->manajemen)->get(route('manajemen.penelitian.index'));

        $response->assertStatus(200);
    }

    public function test_menu_data_pengabdian_masyarakat_dapat_diakses(): void
    {
        $response = $this->actingAs($this->manajemen)->get(route('manajemen.community-services.index'));

        $response->assertStatus(200);
    }

    public function test_menu_tentang_dapat_diakses(): void
    {
        $response = $this->actingAs($this->manajemen)->get(route('about'));

        $response->assertStatus(200);
    }

    public function test_menu_realisasi_anggaran_dapat_diakses(): void
    {
        $response = $this->actingAs($this->manajemen)->get(route('manajemen.research-budgets.index'));

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

    public function test_manajemen_sidebar_contains_expected_menu_items(): void
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
        $response->assertSee('Halaman Depan');
    }

    public function test_manajemen_sidebar_does_not_contain_insight_menu(): void
    {
        $response = $this->actingAs($this->manajemen)->get(route('manajemen.dashboard'));

        $response->assertDontSee('Insight');
    }
}
