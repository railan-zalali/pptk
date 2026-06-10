<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Region;
use App\Models\Garden;
use App\Models\Visit;
use App\Models\VisitPhoto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminVisitTest extends TestCase
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

    public function test_admin_can_view_visits_list(): void
    {
        Visit::factory()->create([
            'garden_id' => $this->garden->id,
            'title' => 'Kunjungan Lapangan',
            'visit_date' => '2026-06-10',
            'status' => 'completed',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.visits.index'));

        $response->assertStatus(200);
        $response->assertSee('Kunjungan Lapangan');
    }

    public function test_admin_can_create_visit_with_photos(): void
    {
        Storage::fake('public');
        $photo1 = UploadedFile::fake()->image('visit1.jpg');
        $photo2 = UploadedFile::fake()->image('visit2.jpg');

        $response = $this->actingAs($this->admin)->post(route('admin.visits.store'), [
            'garden_id' => $this->garden->id,
            'title' => 'Kunjungan Baru',
            'visit_date' => '2026-06-10',
            'duration' => 2,
            'participants_count' => 5,
            'participants_list' => 'Budi, Joko',
            'description' => 'Evaluasi kebun model',
            'objectives' => 'Mengecek pertumbuhan klon',
            'findings' => 'Semua baik',
            'recommendations' => 'Pertahankan',
            'rating' => 5,
            'status' => 'completed',
            'photos' => [$photo1, $photo2],
        ]);

        $response->assertRedirect(route('admin.visits.index'));
        $this->assertDatabaseHas('visits', [
            'title' => 'Kunjungan Baru',
            'garden_id' => $this->garden->id,
            'visitor_name' => $this->admin->name,
        ]);

        $visit = Visit::where('title', 'Kunjungan Baru')->first();
        $this->assertCount(2, $visit->photos);
        foreach ($visit->photos as $p) {
            Storage::disk('public')->assertExists($p->path);
        }
    }

    public function test_admin_can_update_visit(): void
    {
        $visit = Visit::factory()->create([
            'garden_id' => $this->garden->id,
            'title' => 'Kunjungan Lama',
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.visits.update', $visit), [
            'garden_id' => $this->garden->id,
            'title' => 'Kunjungan Update',
            'visit_date' => '2026-06-11',
            'duration' => 3,
            'participants_count' => 6,
            'participants_list' => 'Budi, Joko, Ani',
            'description' => 'Evaluasi kebun model diperbarui',
            'objectives' => 'Mengecek pertumbuhan klon baru',
            'findings' => 'Sangat baik',
            'recommendations' => 'Tingkatkan',
            'rating' => 5,
            'status' => 'completed',
        ]);

        $response->assertRedirect(route('admin.visits.index'));
        $this->assertEquals('Kunjungan Update', $visit->refresh()->title);
    }

    public function test_admin_can_delete_visit(): void
    {
        Storage::fake('public');
        $visit = Visit::factory()->create(['garden_id' => $this->garden->id]);
        $photo = VisitPhoto::create([
            'visit_id' => $visit->id,
            'path' => 'visit-photos/' . $visit->id . '/old.jpg',
            'caption' => 'old.jpg',
        ]);
        Storage::disk('public')->put($photo->path, 'fake content');

        $response = $this->actingAs($this->admin)->delete(route('admin.visits.destroy', $visit));

        $response->assertRedirect(route('admin.visits.index'));
        $this->assertSoftDeleted($visit);
        Storage::disk('public')->assertMissing($photo->path);
    }
}
