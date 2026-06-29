<?php

namespace Tests\Feature;

use App\Models\Region;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Test fungsional untuk semua halaman publik (tanpa login).
 */
class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\CoreStructureSeeder::class);
    }

    /** @test */
    public function test_halaman_beranda_dapat_diakses(): void
    {
        $this->get('/')->assertStatus(200);
    }

    /** @test */
    public function test_halaman_tentang_kebun_model_dapat_diakses(): void
    {
        $this->get(route('about'))->assertStatus(200);
    }

    /** @test */
    public function test_halaman_strategic_action_index_dapat_diakses(): void
    {
        $this->get(route('strategic.index'))->assertStatus(200);
    }

    /** @test */
    public function test_halaman_strategic_action_per_region_dapat_diakses(): void
    {
        $region = Region::first();
        $this->assertNotNull($region, 'CoreStructureSeeder harus menyediakan minimal 1 region.');

        $this->get(route('strategic.region', $region->id))->assertStatus(200);
    }

    /** @test */
    public function test_halaman_strategic_action_per_garden_dapat_diakses(): void
    {
        $region = Region::with('gardens')->first();
        $this->assertNotNull($region);

        $garden = $region->gardens->first();
        $this->assertNotNull($garden, 'Region harus memiliki minimal 1 kebun.');

        $this->get(route('strategic.garden', [$region->id, $garden->id]))->assertStatus(200);
    }

    /** @test */
    public function test_dashboard_publik_kebun_model_dapat_diakses(): void
    {
        $this->get(route('dashboard.garden'))->assertStatus(200);
    }

    /** @test */
    public function test_halaman_daftar_kunjungan_dapat_diakses(): void
    {
        $this->get(route('visits.index'))->assertStatus(200);
    }

    /** @test */
    public function test_halaman_pengabdian_masyarakat_dapat_diakses(): void
    {
        $this->get(route('community-services.index'))->assertStatus(200);
    }

    /** @test */
    public function test_user_belum_login_tidak_bisa_akses_dashboard_internal(): void
    {
        $this->get(route('dashboard'))->assertRedirect(route('login'));
    }
}
