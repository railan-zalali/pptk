<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Test halaman publik (tidak perlu login):
 * - Semua route publik mengembalikan 200
 * - Konten yang diharapkan tampil
 * - Guest tidak melihat menu admin
 */
class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    // ----------------------------------------------------------------
    // Halaman utama
    // ----------------------------------------------------------------

    public function test_home_page_accessible(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
    }

    public function test_home_page_contains_pptk_branding(): void
    {
        $response = $this->get(route('home'));

        $response->assertSee('Portal PPTK Gambung');
    }

    public function test_home_page_contains_navigation_items(): void
    {
        $response = $this->get(route('home'));

        $response->assertSee('Pusat Penelitian Teh dan Kina');
        $response->assertSee('Beranda');
    }

    // ----------------------------------------------------------------
    // Halaman Tentang
    // ----------------------------------------------------------------

    public function test_about_page_accessible(): void
    {
        $response = $this->get(route('about'));

        $response->assertStatus(200);
    }

    // ----------------------------------------------------------------
    // Strategic Action (publik)
    // ----------------------------------------------------------------

    public function test_strategic_index_accessible(): void
    {
        $response = $this->get(route('strategic.index'));

        $response->assertStatus(200);
    }

    // ----------------------------------------------------------------
    // Kunjungan Dinas (publik)
    // ----------------------------------------------------------------

    public function test_visits_index_accessible(): void
    {
        $response = $this->get(route('visits.index'));

        $response->assertStatus(200);
    }

    // ----------------------------------------------------------------
    // Pengabdian Masyarakat (publik)
    // ----------------------------------------------------------------

    public function test_community_services_index_accessible(): void
    {
        $response = $this->get(route('community-services.index'));

        $response->assertStatus(200);
    }

    // ----------------------------------------------------------------
    // Dashboard Publik (Kebun Model — no auth)
    // ----------------------------------------------------------------

    public function test_public_dashboard_accessible_without_auth(): void
    {
        $response = $this->get(route('dashboard.garden'));

        $response->assertStatus(200);
    }

    public function test_public_dashboard_shows_scorecard_labels(): void
    {
        $response = $this->get(route('dashboard.garden'));

        $response->assertSee('Total Produksi');
        $response->assertSee('Produktivitas');
        $response->assertSee('Kualitas');
    }

    // ----------------------------------------------------------------
    // Auth pages
    // ----------------------------------------------------------------

    public function test_login_page_accessible(): void
    {
        $response = $this->get(route('login'));

        $response->assertStatus(200);
    }

    public function test_register_page_accessible(): void
    {
        $response = $this->get(route('register'));

        $response->assertStatus(200);
    }

    // ----------------------------------------------------------------
    // Guest tidak melihat menu admin
    // ----------------------------------------------------------------

    public function test_guest_does_not_see_admin_only_menu(): void
    {
        $response = $this->get(route('home'));

        $response->assertDontSee('Manajemen Pengguna');
        $response->assertDontSee('Realisasi Produksi');
    }

    // ----------------------------------------------------------------
    // Monitoring link tersedia di homepage
    // ----------------------------------------------------------------

    public function test_home_page_has_monitoring_link(): void
    {
        $response = $this->get(route('home'));

        $response->assertSee('Monitoring');
    }
}
