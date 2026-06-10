<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Region;
use App\Models\Garden;
use App\Models\ProductionRealization;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminProductionRealizationTest extends TestCase
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

    public function test_admin_can_view_production_realizations_list(): void
    {
        ProductionRealization::factory()->create([
            'kebun_id' => $this->garden->id,
            'year' => 2026,
            'month' => 1,
            'wet_production_kg' => 5000,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.production-realizations.index'));

        $response->assertStatus(200);
        $response->assertSee('5,000');
    }

    public function test_admin_can_create_production_realization(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.production-realizations.store'), [
            'kebun_id' => $this->garden->id,
            'year' => 2026,
            'month' => 3,
            'active_picking_area_ha' => 80.0,
            'wet_production_kg' => 6000.0,
            'capacity_per_ha' => 75.0,
            'avg_capacity' => 25.0,
            'estimated_production' => 5500.0,
            'quality_score' => 8.0,
        ]);

        $response->assertRedirect(route('admin.production-realizations.index'));
        $this->assertDatabaseHas('production_realizations', [
            'kebun_id' => $this->garden->id,
            'year' => 2026,
            'month' => 3,
            'wet_production_kg' => 6000.0,
        ]);
    }

    public function test_admin_can_update_production_realization(): void
    {
        $realization = ProductionRealization::factory()->create([
            'kebun_id' => $this->garden->id,
            'year' => 2026,
            'month' => 2,
            'wet_production_kg' => 5000.0,
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.production-realizations.update', $realization), [
            'kebun_id' => $this->garden->id,
            'year' => 2026,
            'month' => 2,
            'active_picking_area_ha' => 85.0,
            'wet_production_kg' => 5500.0,
            'capacity_per_ha' => 75.0,
            'avg_capacity' => 25.0,
            'estimated_production' => 5500.0,
            'quality_score' => 8.0,
        ]);

        $response->assertRedirect(route('admin.production-realizations.index'));
        $this->assertEquals(5500.0, $realization->refresh()->wet_production_kg);
    }

    public function test_admin_can_delete_production_realization(): void
    {
        $realization = ProductionRealization::factory()->create(['kebun_id' => $this->garden->id]);

        $response = $this->actingAs($this->admin)->delete(route('admin.production-realizations.destroy', $realization));

        $response->assertRedirect(route('admin.production-realizations.index'));
        $this->assertDatabaseMissing('production_realizations', ['id' => $realization->id]);
    }
}
