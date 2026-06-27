<?php

namespace Tests\Feature;

use App\Models\Garden;
use App\Models\Insight;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Test fungsional untuk dashboard yang membutuhkan autentikasi.
 */
class AuthenticatedDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\DatabaseSeeder::class);
    }

    /** @test */
    public function test_unauthenticated_user_is_redirected_from_dashboard(): void
    {
        $this->get(route('dashboard'))->assertRedirect(route('login'));
    }

    /** @test */
    public function test_admin_pptk_redirected_to_admin_dashboard(): void
    {
        $admin = User::where('role', 'admin_pptk')->first();
        $this->assertNotNull($admin, 'Seeder harus menyediakan user admin_pptk');

        $this->actingAs($admin)
            ->get(route('dashboard'))
            ->assertRedirect(route('admin.dashboard'));
    }

    /** @test */
    public function test_manajemen_redirected_to_manajemen_dashboard(): void
    {
        $manager = User::where('role', 'manajemen')->first();
        $this->assertNotNull($manager, 'Seeder harus menyediakan user manajemen');

        $this->actingAs($manager)
            ->get(route('dashboard'))
            ->assertRedirect(route('manajemen.dashboard'));
    }

    /** @test */
    public function test_admin_dashboard_is_accessible(): void
    {
        $admin = User::where('role', 'admin_pptk')->first();

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertStatus(200);
    }

    /** @test */
    public function test_manajemen_dashboard_is_accessible(): void
    {
        $manager = User::where('role', 'manajemen')->first();

        $this->actingAs($manager)
            ->get(route('manajemen.dashboard'))
            ->assertStatus(200);
    }

    /** @test */
    public function test_admin_pptk_can_access_manajemen_pages(): void
    {
        // Admin PPTK harus bisa akses halaman monitoring manajemen
        $admin = User::where('role', 'admin_pptk')->first();

        $this->actingAs($admin)
            ->get(route('manajemen.dashboard'))
            ->assertStatus(200);
    }

    /** @test */
    public function test_research_dashboard_is_accessible(): void
    {
        $manager = User::where('role', 'manajemen')->first();

        $this->actingAs($manager)
            ->get(route('dashboard.research'))
            ->assertStatus(200);
    }
}
