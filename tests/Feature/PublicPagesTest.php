<?php

namespace Tests\Feature;

use App\Models\Region;
use App\Models\Visit;
use App\Models\VisitPhoto;
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
    public function test_halaman_kunjungan_publik_tidak_menampilkan_tombol_tambah_jadwal(): void
    {
        $this->get(route('visits.index'))
            ->assertStatus(200)
            ->assertDontSee('Tambah Jadwal Baru')
            ->assertDontSee('Jadwal Baru');
    }

    /** @test */
    public function test_form_tambah_jadwal_tidak_tersedia_di_route_publik(): void
    {
        $this->get('/kunjungan-dinas/create')
            ->assertStatus(404);
    }

    /** @test */
    public function test_data_kalender_kunjungan_memuat_foto_jika_tersedia(): void
    {
        $garden = Region::with('gardens')->firstOrFail()->gardens->first();

        $visit = Visit::create([
            'garden_id' => $garden->id,
            'title' => 'Kunjungan Foto',
            'visit_date' => '2026-06-15',
            'duration' => 2,
            'participants_count' => 3,
            'description' => 'Agenda kunjungan dengan foto.',
            'rating' => 5,
            'visitor_name' => 'Tim PPTK',
            'purpose' => 'Dokumentasi',
            'status' => 'scheduled',
        ]);

        VisitPhoto::create([
            'visit_id' => $visit->id,
            'path' => 'visit-photos/testing/foto.jpg',
            'caption' => 'Foto jadwal',
        ]);

        $this->getJson(route('visits.index', ['month' => 6, 'year' => 2026]), [
            'X-Requested-With' => 'XMLHttpRequest',
        ])
            ->assertStatus(200)
            ->assertJsonFragment([
                'caption' => 'Foto jadwal',
                'photo_url' => asset('storage/visit-photos/testing/foto.jpg'),
            ]);
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
