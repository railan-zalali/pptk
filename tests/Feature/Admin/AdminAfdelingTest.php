<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Region;
use App\Models\Garden;
use App\Models\Afdeling;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAfdelingTest extends TestCase
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

    public function test_admin_can_view_afdelings_list(): void
    {
        Afdeling::factory()->create([
            'kebun_id' => $this->garden->id,
            'name' => 'Afdeling Barat',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.afdelings.index'));

        $response->assertStatus(200);
        $response->assertSee('Afdeling Barat');
    }

    public function test_admin_can_create_afdeling(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.afdelings.store'), [
            'kebun_id' => $this->garden->id,
            'name' => 'Afdeling Baru',
            'total_area_ha' => 50.0,
            'tm_area_ha' => 45.0,
        ]);

        $response->assertRedirect(route('admin.afdelings.index'));
        $this->assertDatabaseHas('afdelings', [
            'name' => 'Afdeling Baru',
            'kebun_id' => $this->garden->id,
        ]);
    }

    public function test_admin_can_update_afdeling(): void
    {
        $afdeling = Afdeling::factory()->create([
            'kebun_id' => $this->garden->id,
            'name' => 'Afdeling Lama',
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.afdelings.update', $afdeling), [
            'kebun_id' => $this->garden->id,
            'name' => 'Afdeling Update',
            'total_area_ha' => 60.0,
            'tm_area_ha' => 55.0,
        ]);

        $response->assertRedirect(route('admin.afdelings.index'));
        $this->assertEquals('Afdeling Update', $afdeling->refresh()->name);
    }

    public function test_admin_can_delete_afdeling(): void
    {
        $afdeling = Afdeling::factory()->create(['kebun_id' => $this->garden->id]);

        $response = $this->actingAs($this->admin)->delete(route('admin.afdelings.destroy', $afdeling));

        $response->assertRedirect(route('admin.afdelings.index'));
        $this->assertSoftDeleted($afdeling);
    }
}
