<?php

namespace Tests\Feature;

use App\Models\Garden;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GardenAndDataTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\PPTKSeeder::class);
    }

    public function test_home_page_is_accessible(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Portal PPTK Gambung');
    }

    public function test_strategic_pages_are_accessible(): void
    {
        $region = \App\Models\Region::first();
        $garden = Garden::first();

        $this->assertNotNull($region);
        $this->assertNotNull($garden);

        $this->get(route('strategic.index'))->assertStatus(200);
        $this->get(route('strategic.region', $region->id))->assertStatus(200);
        $response = $this->get(route('strategic.garden', [$region->id, $garden->id]));
        if ($response->status() !== 200) {
            $response->dump();
        }
        $response->assertStatus(200);
    }

    public function test_dashboard_pages_are_accessible_for_authenticated_user(): void
    {
        $user = User::first();
        $this->actingAs($user);

        $this->get(route('dashboard.garden'))->assertStatus(200);
        $this->get(route('dashboard.research'))->assertStatus(200);
    }
}
