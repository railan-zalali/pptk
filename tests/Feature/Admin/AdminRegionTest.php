<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Region;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminRegionTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    public function test_admin_can_view_regions_list(): void
    {
        Region::factory()->create([
            'regional_name' => 'Wilayah Utara',
            'province' => 'Jawa Tengah'
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.regions.index'));

        $response->assertStatus(200);
        $response->assertSee('Wilayah Utara');
    }

    public function test_admin_can_search_regions(): void
    {
        Region::factory()->create([
            'regional_name' => 'Wilayah Barat',
            'province' => 'Jawa Barat'
        ]);
        Region::factory()->create([
            'regional_name' => 'Wilayah Timur',
            'province' => 'Jawa Timur'
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.regions.index', ['search' => 'Barat']));

        $response->assertStatus(200);
        $response->assertSee('Wilayah Barat');
        $response->assertDontSee('Wilayah Timur');
    }

    public function test_admin_can_create_region_with_photo(): void
    {
        Storage::fake('public');

        $photo = UploadedFile::fake()->image('region.jpg');
        $additionalPhoto1 = UploadedFile::fake()->image('additional1.jpg');
        $additionalPhoto2 = UploadedFile::fake()->image('additional2.jpg');

        $response = $this->actingAs($this->admin)->post(route('admin.regions.store'), [
            'regional_name' => 'Wilayah Baru',
            'province' => 'Banten',
            'coordinates' => '-6.12,106.15',
            'photo' => $photo,
            'photos' => [$additionalPhoto1, $additionalPhoto2],
        ]);

        $response->assertRedirect(route('admin.regions.index'));
        $this->assertDatabaseHas('regions', [
            'regional_name' => 'Wilayah Baru',
            'regional_code' => 'WILAYAH_BARU',
            'province' => 'Banten',
        ]);

        $region = Region::where('regional_name', 'Wilayah Baru')->first();
        $this->assertNotNull($region->photo_path);
        Storage::disk('public')->assertExists($region->photo_path);

        $this->assertCount(2, $region->photos);
        foreach ($region->photos as $p) {
            Storage::disk('public')->assertExists($p->path);
        }
    }

    public function test_admin_can_update_region(): void
    {
        Storage::fake('public');
        $region = Region::factory()->create([
            'regional_name' => 'Wilayah Lama',
            'photo_path' => 'region-photos/old.jpg',
            'province' => 'Jawa Barat',
        ]);
        Storage::disk('public')->put('region-photos/old.jpg', 'fake content');

        $newPhoto = UploadedFile::fake()->image('new_region.jpg');

        $response = $this->actingAs($this->admin)->put(route('admin.regions.update', $region), [
            'regional_name' => 'Wilayah Update',
            'province' => 'Jawa Barat',
            'photo' => $newPhoto,
        ]);

        $response->assertRedirect(route('admin.regions.index'));
        $region->refresh();

        $this->assertEquals('Wilayah Update', $region->regional_name);
        $this->assertEquals('WILAYAH_UPDATE', $region->regional_code);
        Storage::disk('public')->assertMissing('region-photos/old.jpg');
        Storage::disk('public')->assertExists($region->photo_path);
    }

    public function test_admin_can_delete_region(): void
    {
        Storage::fake('public');
        $region = Region::factory()->create([
            'photo_path' => 'region-photos/old.jpg',
            'province' => 'Jawa Barat',
        ]);
        Storage::disk('public')->put('region-photos/old.jpg', 'fake content');

        $response = $this->actingAs($this->admin)->delete(route('admin.regions.destroy', $region));

        $response->assertRedirect(route('admin.regions.index'));
        $this->assertSoftDeleted($region);
        Storage::disk('public')->assertMissing('region-photos/old.jpg');
    }
}
