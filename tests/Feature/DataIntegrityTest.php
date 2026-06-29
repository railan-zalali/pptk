<?php

namespace Tests\Feature;

use App\Models\Afdeling;
use App\Models\Block;
use App\Models\CommunityService;
use App\Models\Garden;
use App\Models\ProductionRealization;
use App\Models\Region;
use App\Models\StrategicAction;
use App\Models\Visit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Test integritas data setelah proses seeding.
 * Memverifikasi bahwa semua relasi dan data penting terisi dengan benar.
 */
class DataIntegrityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\DatabaseSeeder::class);
    }

    // ─── Struktur Kebun ────────────────────────────────────────────────────

    /** @test */
    public function test_setiap_garden_memiliki_region(): void
    {
        $garden = Garden::with('region')->first();
        $this->assertNotNull($garden, 'Harus ada minimal 1 kebun setelah seeding.');
        $this->assertNotNull($garden->region, 'Setiap kebun harus memiliki region.');
    }

    /** @test */
    public function test_setiap_garden_memiliki_afdeling(): void
    {
        Garden::with('afdelings')->each(function (Garden $garden) {
            $this->assertTrue(
                $garden->afdelings->isNotEmpty(),
                "Kebun [{$garden->kebun_name}] harus memiliki minimal 1 afdeling."
            );
        });
    }

    /** @test */
    public function test_setiap_afdeling_memiliki_blok(): void
    {
        Afdeling::with('blocks')->each(function (Afdeling $afdeling) {
            $this->assertTrue(
                $afdeling->blocks->isNotEmpty(),
                "Afdeling [{$afdeling->name}] harus memiliki minimal 1 blok."
            );
        });
    }

    /** @test */
    public function test_jumlah_region_sesuai_ekspektasi(): void
    {
        $this->assertEquals(2, Region::count(), 'Harus ada 2 region.');
    }

    /** @test */
    public function test_jumlah_garden_sesuai_ekspektasi(): void
    {
        $this->assertEquals(4, Garden::count(), 'Harus ada 4 kebun (2 per region).');
    }

    /** @test */
    public function test_jumlah_afdeling_sesuai_ekspektasi(): void
    {
        $this->assertEquals(12, Afdeling::count(), 'Harus ada 12 afdeling (3 per kebun).');
    }

    /** @test */
    public function test_jumlah_blok_sesuai_ekspektasi(): void
    {
        $this->assertEquals(48, Block::count(), 'Harus ada 48 blok (4 per afdeling).');
    }

    // ─── Data Produksi ─────────────────────────────────────────────────────

    /** @test */
    public function test_realisasi_produksi_tersedia_untuk_semua_kebun(): void
    {
        $gardens = Garden::all();
        foreach ($gardens as $garden) {
            $count = ProductionRealization::where('kebun_id', $garden->id)->count();
            $this->assertGreaterThan(0, $count, "Kebun [{$garden->kebun_name}] harus punya data produksi.");
        }
    }

    // ─── Strategic Actions ─────────────────────────────────────────────────

    /** @test */
    public function test_setiap_garden_memiliki_strategic_action(): void
    {
        $garden = Garden::with('strategicActions')->first();
        $this->assertTrue(
            $garden->strategicActions->isNotEmpty(),
            'Setiap kebun harus punya strategic actions setelah seeding.'
        );
    }

    /** @test */
    public function test_strategic_action_memiliki_realization_percent(): void
    {
        $count = StrategicAction::whereNotNull('realization_percent')->count();
        $this->assertGreaterThan(0, $count, 'Strategic actions harus memiliki realization_percent.');
    }

    // ─── Kunjungan & Pengabdian ────────────────────────────────────────────

    /** @test */
    public function test_kunjungan_tersedia_setelah_seeding(): void
    {
        $this->assertGreaterThan(0, Visit::count(), 'Harus ada data kunjungan setelah seeding.');
    }

    /** @test */
    public function test_pengabdian_masyarakat_tersedia_setelah_seeding(): void
    {
        $this->assertGreaterThan(0, CommunityService::count(), 'Harus ada data pengabdian masyarakat.');
    }

    /** @test */
    public function test_setiap_kunjungan_memiliki_relasi_garden(): void
    {
        Visit::with('garden')->each(function (Visit $visit) {
            $this->assertNotNull($visit->garden, "Kunjungan ID [{$visit->id}] harus memiliki relasi garden.");
        });
    }
}
