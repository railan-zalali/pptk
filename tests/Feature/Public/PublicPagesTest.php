<?php

namespace Tests\Feature\Public;

use App\Models\Region;
use App\Models\Garden;
use App\Models\Visit;
use App\Models\CommunityService;
use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    protected Region $region;
    protected Garden $garden;

    protected function setUp(): void
    {
        parent::setUp();

        $this->region = Region::factory()->create([
            'regional_name' => 'Wilayah Barat',
            'regional_code' => 'REG_BARAT',
            'province'      => 'Jawa Barat',
        ]);

        $this->garden = Garden::factory()->create([
            'regional_id'   => $this->region->id,
            'kebun_name'    => 'Kebun Rancabali',
            'kebun_type'    => 'Model',
        ]);

        // Seed some basic CMS pages
        Page::create([
            'slug' => 'about',
            'title' => 'Tentang Kebun Model',
            'content' => 'Konten tentang kebun model',
            'meta' => [],
        ]);

        Page::create([
            'slug' => 'research',
            'title' => 'Penelitian',
            'content' => 'Konten penelitian',
            'meta' => [],
        ]);
    }

    public function test_home_page_is_accessible(): void
    {
        $response = $this->get(route('home'));
        $response->assertStatus(200);
    }

    public function test_about_page_is_accessible(): void
    {
        $response = $this->get(route('about'));
        $response->assertStatus(200);
        $response->assertSee('Tentang Kebun Model');
    }

    public function test_strategic_action_pages_are_accessible(): void
    {
        $response = $this->get(route('strategic.index'));
        $response->assertStatus(200);

        $response = $this->get(route('strategic.region', $this->region));
        $response->assertStatus(200);

        $response = $this->get(route('strategic.garden', [$this->region, $this->garden]));
        $response->assertStatus(200);
    }

    public function test_public_visits_pages_are_accessible(): void
    {
        $visit = Visit::factory()->create([
            'garden_id'  => $this->garden->id,
            'title'      => 'Kunjungan Dinas A',
            'status'     => 'completed',
            'visit_date' => '2026-06-10',
        ]);

        // HTML response should load the page skeleton
        $response = $this->get(route('visits.index'));
        $response->assertStatus(200);
        $response->assertSee('Jadwal Kunjungan');

        // AJAX response should contain the event data
        $responseAjax = $this->get(route('visits.index', ['month' => 6, 'year' => 2026]), [
            'HTTP_X-Requested-With' => 'XMLHttpRequest'
        ]);
        $responseAjax->assertStatus(200);
        $responseAjax->assertJsonFragment(['title' => 'Kunjungan Dinas A']);

        // Show page should contain details
        $responseShow = $this->get(route('visits.show', $visit));
        $responseShow->assertStatus(200);
        $responseShow->assertSee('Kunjungan Dinas A');
    }

    public function test_public_community_services_page_is_accessible(): void
    {
        CommunityService::factory()->create([
            'garden_id' => $this->garden->id,
            'activity_name' => 'Bakti Sosial A',
            'status' => 'completed',
        ]);

        $response = $this->get(route('community-services.index'));
        $response->assertStatus(200);
        $response->assertSee('Bakti Sosial A');
    }

    public function test_public_dashboard_is_accessible(): void
    {
        $response = $this->get(route('dashboard.garden'));
        $response->assertStatus(200);
    }
}
