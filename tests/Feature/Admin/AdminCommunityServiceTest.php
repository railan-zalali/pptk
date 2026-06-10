<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Region;
use App\Models\Garden;
use App\Models\CommunityService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCommunityServiceTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected Garden $garden;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
        $region = Region::factory()->create(['province' => 'Jawa Barat']);
        $this->garden = Garden::factory()->create(['regional_id' => $region->id]);
    }

    public function test_admin_can_view_community_services_list(): void
    {
        CommunityService::factory()->create([
            'garden_id' => $this->garden->id,
            'activity_name' => 'Baksos Kebun',
            'status' => 'completed',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.community-services.index'));

        $response->assertStatus(200);
        $response->assertSee('Baksos Kebun');
    }

    public function test_admin_can_create_community_service(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.community-services.store'), [
            'garden_id' => $this->garden->id,
            'activity_name' => 'Pemberdayaan Masyarakat',
            'team_name' => 'CSR Team',
            'total_budget' => 50000000,
            'remaining_budget' => 10000000,
            'year' => 2026,
            'status' => 'ongoing',
            'start_date' => '2026-06-01',
            'end_date' => '2026-06-30',
            'description' => 'Program CSR tahunan',
            'location' => 'Desa Rancabali',
        ]);

        $response->assertRedirect(route('admin.community-services.index'));
        $this->assertDatabaseHas('community_services', [
            'activity_name' => 'Pemberdayaan Masyarakat',
            'garden_id' => $this->garden->id,
            'status' => 'ongoing',
        ]);
    }

    public function test_admin_can_update_community_service(): void
    {
        $service = CommunityService::factory()->create([
            'garden_id' => $this->garden->id,
            'activity_name' => 'Kegiatan Lama',
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.community-services.update', $service), [
            'garden_id' => $this->garden->id,
            'activity_name' => 'Kegiatan Baru',
            'team_name' => 'CSR Team',
            'total_budget' => 60000000,
            'remaining_budget' => 20000000,
            'year' => 2026,
            'status' => 'completed',
            'start_date' => '2026-06-01',
            'end_date' => '2026-06-30',
            'description' => 'Program CSR tahunan diperbarui',
            'location' => 'Desa Rancabali',
        ]);

        $response->assertRedirect(route('admin.community-services.index'));
        $this->assertEquals('Kegiatan Baru', $service->refresh()->activity_name);
    }

    public function test_admin_can_delete_community_service(): void
    {
        $service = CommunityService::factory()->create(['garden_id' => $this->garden->id]);

        $response = $this->actingAs($this->admin)->delete(route('admin.community-services.destroy', $service));

        $response->assertRedirect(route('admin.community-services.index'));
        $this->assertSoftDeleted($service);
    }
}
