<?php

namespace Tests\Feature;

use App\Models\Garden;
use App\Models\Region;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Test fungsional untuk halaman publik sistem PPTK.
 * Menggunakan seeder agar data konsisten.
 */
class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\DatabaseSeeder::class);
    }

    /** @test */
    public function test_home_page_is_accessible(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    /** @test */
    public function test_strategic_index_page_is_accessible(): void
    {
        $this->get(route('strategic.index'))->assertStatus(200);
    }

    /** @test */
    public function test_strategic_region_page_is_accessible(): void
    {
        $region = Region::first();
        $this->assertNotNull($region, 'Seeder harus menyediakan setidaknya satu region');

        $this->get(route('strategic.region', $region->id))->assertStatus(200);
    }

    /** @test */
    public function test_strategic_garden_page_uses_correct_region(): void
    {
        // Garden harus dipilih dari region yang SAMA agar route binding benar
        $region = Region::with('gardens')->first();
        $this->assertNotNull($region);

        $garden = $region->gardens->first();
        $this->assertNotNull($garden, 'Region harus memiliki setidaknya satu kebun');

        $response = $this->get(route('strategic.garden', [$region->id, $garden->id]));
        $response->assertStatus(200);
    }

    /** @test */
    public function test_public_dashboard_kebun_model_is_accessible(): void
    {
        $this->get(route('dashboard.garden'))->assertStatus(200);
    }

    /** @test */
    public function test_visits_index_is_accessible(): void
    {
        $this->get(route('visits.index'))->assertStatus(200);
    }
}
