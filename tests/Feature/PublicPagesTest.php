<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_can_be_rendered(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('Portal PPTK Gambung');
    }

    public function test_about_page_can_be_rendered(): void
    {
        $response = $this->get(route('about'));

        $response->assertStatus(200);
    }

    public function test_strategic_index_can_be_rendered(): void
    {
        $response = $this->get(route('strategic.index'));

        $response->assertStatus(200);
    }

    public function test_visits_index_can_be_rendered(): void
    {
        $response = $this->get(route('visits.index'));

        $response->assertStatus(200);
    }

    public function test_community_services_index_can_be_rendered(): void
    {
        $response = $this->get(route('community-services.index'));

        $response->assertStatus(200);
    }

    public function test_public_dashboard_can_be_rendered(): void
    {
        $response = $this->get(route('dashboard.garden'));

        $response->assertStatus(200);
    }

    public function test_login_page_can_be_rendered(): void
    {
        $response = $this->get(route('login'));

        $response->assertStatus(200);
    }

    public function test_register_page_can_be_rendered(): void
    {
        $response = $this->get(route('register'));

        $response->assertStatus(200);
    }

    public function test_public_pages_contain_expected_content(): void
    {
        $response = $this->get(route('home'));

        $response->assertSee('Pusat Penelitian Teh dan Kina');
        $response->assertSee('Monitoring');
        $response->assertSee('Beranda');
    }

    public function test_guest_cannot_see_admin_menu(): void
    {
        $response = $this->get(route('home'));

        $response->assertDontSee('Manajemen Pengguna');
        $response->assertDontSee('Realisasi Produksi');
    }

    public function test_public_dashboard_has_scorecards(): void
    {
        $response = $this->get(route('dashboard.garden'));

        $response->assertSee('Total Produksi');
        $response->assertSee('Produktivitas');
        $response->assertSee('Kualitas');
    }

    public function test_visits_index_has_calendar(): void
    {
        $response = $this->get(route('visits.index'));

        $response->assertStatus(200);
    }
}
