<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_has_admin_pptk_role(): void
    {
        $user = User::factory()->create(['role' => 'admin_pptk']);

        $this->assertTrue($user->isAdminPptk());
        $this->assertFalse($user->isManajemen());
    }

    public function test_user_has_manajemen_role(): void
    {
        $user = User::factory()->create(['role' => 'manajemen']);

        $this->assertTrue($user->isManajemen());
        $this->assertFalse($user->isAdminPptk());
    }

    public function test_user_is_admin_alias(): void
    {
        $user = User::factory()->create(['role' => 'admin_pptk']);

        $this->assertTrue($user->isAdmin());
    }

    public function test_user_is_manager_alias(): void
    {
        $user = User::factory()->create(['role' => 'manajemen']);

        $this->assertTrue($user->isManager());
    }

    public function test_user_role_attribute(): void
    {
        $user = User::factory()->create(['role' => 'admin_pptk']);

        $this->assertEquals('admin_pptk', $user->role);
    }

    public function test_user_default_role_is_manajemen(): void
    {
        $user = User::factory()->create();

        $this->assertEquals('manajemen', $user->role);
    }

    public function test_user_serialization_hides_sensitive_attributes(): void
    {
        $user = User::factory()->create();
        $serialized = $user->toArray();

        $this->assertArrayNotHasKey('password', $serialized);
        $this->assertArrayNotHasKey('remember_token', $serialized);
    }

    public function test_user_has_timestamps(): void
    {
        $user = User::factory()->create();

        $this->assertNotNull($user->created_at);
        $this->assertNotNull($user->updated_at);
    }
}
