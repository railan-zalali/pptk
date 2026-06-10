<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Region;
use App\Models\Garden;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminGardenTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected Region $region;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->region = Region::factory()->create(['province' => 'Jawa Barat']);
    }

    public function test_admin_can_view_gardens_list(): void
    {
        Garden::factory()->create([
            'regional_id' => $this->region->id,
            'kebun_name' => 'Kebun Rancabali',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.gardens.index'));

        $response->assertStatus(200);
        $response->assertSee('Kebun Rancabali');
    }

    public function test_admin_can_filter_gardens(): void
    {
        $garden1 = Garden::factory()->create([
            'regional_id' => $this->region->id,
            'kebun_name' => 'Kebun Rancabali',
            'kebun_type' => 'Model',
        ]);
        $garden2 = Garden::factory()->create([
            'regional_id' => $this->region->id,
            'kebun_name' => 'Kebun Malabar',
            'kebun_type' => 'Pengembangan',
        ]);

        // Filter by search
        $response = $this->actingAs($this->admin)->get(route('admin.gardens.index', ['search' => 'Rancabali']));
        $response->assertSee('Kebun Rancabali');
        $response->assertDontSee('Kebun Malabar');

        // Filter by kebun_type
        $response = $this->actingAs($this->admin)->get(route('admin.gardens.index', ['kebun_type' => 'Pengembangan']));
        $response->assertSee('Kebun Malabar');
        $response->assertDontSee('Kebun Rancabali');
    }

    public function test_admin_can_create_garden_with_photo(): void
    {
        Storage::fake('public');
        $photo = UploadedFile::fake()->image('garden.jpg');

        $response = $this->actingAs($this->admin)->post(route('admin.gardens.store'), [
            'regional_id' => $this->region->id,
            'kebun_name' => 'Kebun Anyar',
            'luas_total_ha' => 120.0,
            'location' => 'Bandung',
            'kebun_type' => 'Model',
            'photo' => $photo,
        ]);

        $response->assertRedirect(route('admin.gardens.index'));
        $this->assertDatabaseHas('gardens', [
            'kebun_name' => 'Kebun Anyar',
            'regional_id' => $this->region->id,
        ]);

        $garden = Garden::where('kebun_name', 'Kebun Anyar')->first();
        $this->assertNotNull($garden->photo_path);
        Storage::disk('public')->assertExists($garden->photo_path);
    }

    public function test_admin_can_update_garden(): void
    {
        Storage::fake('public');
        $garden = Garden::factory()->create([
            'regional_id' => $this->region->id,
            'kebun_name' => 'Kebun Lama',
            'photo_path' => 'garden-photos/old.jpg',
        ]);
        Storage::disk('public')->put('garden-photos/old.jpg', 'fake content');

        $newPhoto = UploadedFile::fake()->image('new_garden.jpg');

        $response = $this->actingAs($this->admin)->put(route('admin.gardens.update', $garden), [
            'regional_id' => $this->region->id,
            'kebun_name' => 'Kebun Update',
            'luas_total_ha' => 95.0,
            'location' => 'Bandung',
            'kebun_type' => 'Model',
            'photo' => $newPhoto,
        ]);

        $response->assertRedirect(route('admin.gardens.index'));
        $garden->refresh();

        $this->assertEquals('Kebun Update', $garden->kebun_name);
        Storage::disk('public')->assertMissing('garden-photos/old.jpg');
        Storage::disk('public')->assertExists($garden->photo_path);
    }

    public function test_admin_can_delete_garden(): void
    {
        Storage::fake('public');
        $garden = Garden::factory()->create([
            'regional_id' => $this->region->id,
            'photo_path' => 'garden-photos/old.jpg',
        ]);
        Storage::disk('public')->put('garden-photos/old.jpg', 'fake content');

        $response = $this->actingAs($this->admin)->delete(route('admin.gardens.destroy', $garden));

        $response->assertRedirect(route('admin.gardens.index'));
        $this->assertSoftDeleted($garden);
        Storage::disk('public')->assertMissing('garden-photos/old.jpg');
    }
}
