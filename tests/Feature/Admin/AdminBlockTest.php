<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Region;
use App\Models\Garden;
use App\Models\Afdeling;
use App\Models\Block;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminBlockTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected Afdeling $afdeling;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
        $region = Region::factory()->create(['province' => 'Jawa Barat']);
        $garden = Garden::factory()->create(['regional_id' => $region->id]);
        $this->afdeling = Afdeling::factory()->create(['kebun_id' => $garden->id]);
    }

    public function test_admin_can_view_blocks_list(): void
    {
        Block::factory()->create([
            'afdeling_id' => $this->afdeling->id,
            'name' => 'Blok Utara',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.blocks.index'));

        $response->assertStatus(200);
        $response->assertSee('Blok Utara');
    }

    public function test_admin_can_create_block(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.blocks.store'), [
            'afdeling_id' => $this->afdeling->id,
            'name' => 'Blok Baru',
            'code' => 'BLK-NEW-01',
            'area_ha' => 10.5,
            'population' => 5000,
            'plant_type' => 'klon_gmb',
            'planting_year' => 2010,
            'initial_class' => 'A',
            'topography' => 'datar',
        ]);

        $response->assertRedirect(route('admin.blocks.index'));
        $this->assertDatabaseHas('blocks', [
            'name' => 'Blok Baru',
            'code' => 'BLK-NEW-01',
            'afdeling_id' => $this->afdeling->id,
        ]);
    }

    public function test_admin_can_update_block(): void
    {
        $block = Block::factory()->create([
            'afdeling_id' => $this->afdeling->id,
            'name' => 'Blok Lama',
            'code' => 'BLK-OLD-01',
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.blocks.update', $block), [
            'afdeling_id' => $this->afdeling->id,
            'name' => 'Blok Update',
            'code' => 'BLK-OLD-01',
            'area_ha' => 12.5,
            'population' => 5500,
            'plant_type' => 'klon_gmb',
            'planting_year' => 2012,
            'initial_class' => 'B',
            'topography' => 'gelombang',
        ]);

        $response->assertRedirect(route('admin.blocks.index'));
        $this->assertEquals('Blok Update', $block->refresh()->name);
    }

    public function test_admin_can_delete_block(): void
    {
        $block = Block::factory()->create(['afdeling_id' => $this->afdeling->id]);

        $response = $this->actingAs($this->admin)->delete(route('admin.blocks.destroy', $block));

        $response->assertRedirect(route('admin.blocks.index'));
        $this->assertSoftDeleted($block);
    }
}
