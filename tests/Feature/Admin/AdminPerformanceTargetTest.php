<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Region;
use App\Models\Garden;
use App\Models\PerformanceTarget;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPerformanceTargetTest extends TestCase
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

    public function test_admin_can_view_performance_targets_list(): void
    {
        PerformanceTarget::factory()->create([
            'kebun_id' => $this->garden->id,
            'year' => 2026,
            'target_protas_min' => 1200.0,
            'target_protas_max' => 1500.0,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.performance-targets.index'));

        $response->assertStatus(200);
        $response->assertSee('1,200.00');
    }

    public function test_admin_can_create_performance_target(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.performance-targets.store'), [
            'kebun_id' => $this->garden->id,
            'year' => 2026,
            'target_protas_min' => 1000.0,
            'target_protas_max' => 1200.0,
            'note' => 'Optimistic target',
        ]);

        $response->assertRedirect(route('admin.performance-targets.index'));
        $this->assertDatabaseHas('performance_targets', [
            'kebun_id' => $this->garden->id,
            'year' => 2026,
            'target_protas_min' => 1000.0,
        ]);
    }

    public function test_admin_can_update_performance_target(): void
    {
        $target = PerformanceTarget::factory()->create([
            'kebun_id' => $this->garden->id,
            'year' => 2026,
            'target_protas_min' => 1000.0,
            'target_protas_max' => 1200.0,
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.performance-targets.update', $target), [
            'kebun_id' => $this->garden->id,
            'year' => 2026,
            'target_protas_min' => 1100.0,
            'target_protas_max' => 1300.0,
            'note' => 'Adjusted target',
        ]);

        $response->assertRedirect(route('admin.performance-targets.index'));
        $this->assertEquals(1100.0, $target->refresh()->target_protas_min);
    }

    public function test_admin_can_delete_performance_target(): void
    {
        $target = PerformanceTarget::factory()->create(['kebun_id' => $this->garden->id]);

        $response = $this->actingAs($this->admin)->delete(route('admin.performance-targets.destroy', $target));

        $response->assertRedirect(route('admin.performance-targets.index'));
        $this->assertSoftDeleted($target);
    }
}
