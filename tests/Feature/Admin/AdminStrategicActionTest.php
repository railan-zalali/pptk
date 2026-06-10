<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Region;
use App\Models\Garden;
use App\Models\Program;
use App\Models\StrategicAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminStrategicActionTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected Garden $garden;
    protected Program $program;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
        $region = Region::factory()->create(['province' => 'Jawa Barat']);
        $this->garden = Garden::factory()->create(['regional_id' => $region->id]);
        $this->program = Program::factory()->create(['status' => true]);
    }

    public function test_admin_can_view_strategic_actions_list(): void
    {
        StrategicAction::factory()->create([
            'kebun_id' => $this->garden->id,
            'program_id' => $this->program->id,
            'action_type' => 'weed_control',
            'year' => 2026,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.strategic-actions.index'));

        $response->assertStatus(200);
        $response->assertSee('weed_control');
    }

    public function test_admin_can_create_strategic_action(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.strategic-actions.store'), [
            'kebun_id' => $this->garden->id,
            'program_id' => $this->program->id,
            'year' => 2026,
            'action_type' => 'fertilizer_root',
            'status' => 'planned',
            'coverage_target_percent' => 80.0,
            'realization_percent' => 0.0,
            'dosis_n_kg_ha' => 150.0,
            'realized_dosis_n_kg_ha' => 0.0,
            'n_protas_percent' => 0.0,
        ]);

        $response->assertRedirect(route('admin.strategic-actions.index'));
        $this->assertDatabaseHas('strategic_actions', [
            'kebun_id' => $this->garden->id,
            'program_id' => $this->program->id,
            'action_type' => 'fertilizer_root',
            'year' => 2026,
        ]);
    }

    public function test_admin_can_update_strategic_action(): void
    {
        $action = StrategicAction::factory()->create([
            'kebun_id' => $this->garden->id,
            'program_id' => $this->program->id,
            'action_type' => 'fertilizer_root',
            'year' => 2026,
            'status' => 'planned',
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.strategic-actions.update', $action), [
            'kebun_id' => $this->garden->id,
            'program_id' => $this->program->id,
            'year' => 2026,
            'action_type' => 'fertilizer_root',
            'status' => 'completed',
            'coverage_target_percent' => 80.0,
            'realization_percent' => 80.0,
            'dosis_n_kg_ha' => 150.0,
            'realized_dosis_n_kg_ha' => 150.0,
            'n_protas_percent' => 90.0,
        ]);

        $response->assertRedirect(route('admin.strategic-actions.index'));
        $this->assertEquals('completed', $action->refresh()->status);
    }

    public function test_admin_can_delete_strategic_action(): void
    {
        $action = StrategicAction::factory()->create([
            'kebun_id' => $this->garden->id,
            'program_id' => $this->program->id,
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.strategic-actions.destroy', $action));

        $response->assertRedirect(route('admin.strategic-actions.index'));
        $this->assertDatabaseMissing('strategic_actions', ['id' => $action->id]);
    }
}
