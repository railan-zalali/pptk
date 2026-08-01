<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserModelTest extends TestCase
{
    use RefreshDatabase;

    // ----------------------------------------------------------------
    // Role: admin_pptk
    // ----------------------------------------------------------------

    public function test_admin_pptk_role_returns_true_for_isAdminPptk(): void
    {
        $user = User::factory()->create(['role' => 'admin_pptk']);

        $this->assertTrue($user->isAdminPptk());
    }

    public function test_admin_pptk_role_returns_false_for_isManajemen(): void
    {
        $user = User::factory()->create(['role' => 'admin_pptk']);

        $this->assertFalse($user->isManajemen());
    }

    // ----------------------------------------------------------------
    // Role: manajemen
    // ----------------------------------------------------------------

    public function test_manajemen_role_returns_true_for_isManajemen(): void
    {
        $user = User::factory()->create(['role' => 'manajemen']);

        $this->assertTrue($user->isManajemen());
    }

    public function test_manajemen_role_returns_false_for_isAdminPptk(): void
    {
        $user = User::factory()->create(['role' => 'manajemen']);

        $this->assertFalse($user->isAdminPptk());
    }

    // ----------------------------------------------------------------
    // isPrivileged — kedua role punya akses panel
    // ----------------------------------------------------------------

    public function test_admin_pptk_is_privileged(): void
    {
        $user = User::factory()->create(['role' => 'admin_pptk']);

        $this->assertTrue($user->isPrivileged());
    }

    public function test_manajemen_is_privileged(): void
    {
        $user = User::factory()->create(['role' => 'manajemen']);

        $this->assertTrue($user->isPrivileged());
    }

    // ----------------------------------------------------------------
    // isAdmin — deprecated alias, perilaku sama dengan isPrivileged
    // ----------------------------------------------------------------

    public function test_isAdmin_returns_true_for_admin_pptk(): void
    {
        $user = User::factory()->create(['role' => 'admin_pptk']);

        $this->assertTrue($user->isAdmin());
    }

    public function test_isAdmin_returns_true_for_manajemen(): void
    {
        // isAdmin() adalah alias isPrivileged(), bukan isAdminPptk()
        $user = User::factory()->create(['role' => 'manajemen']);

        $this->assertTrue($user->isAdmin());
    }

    // ----------------------------------------------------------------
    // isManager — alias untuk isManajemen
    // ----------------------------------------------------------------

    public function test_isManager_alias_returns_true_for_manajemen(): void
    {
        $user = User::factory()->create(['role' => 'manajemen']);

        $this->assertTrue($user->isManager());
    }

    public function test_isManager_returns_false_for_admin_pptk(): void
    {
        $user = User::factory()->create(['role' => 'admin_pptk']);

        $this->assertFalse($user->isManager());
    }

    // ----------------------------------------------------------------
    // Role attribute & factory default
    // ----------------------------------------------------------------

    public function test_role_attribute_persisted_correctly(): void
    {
        $user = User::factory()->create(['role' => 'admin_pptk']);

        $this->assertEquals('admin_pptk', $user->role);
    }

    public function test_factory_default_role_is_manajemen(): void
    {
        // UserFactory default role harus sesuai dengan enum DB
        $user = User::factory()->create();

        $this->assertEquals('manajemen', $user->role);
    }

    // ----------------------------------------------------------------
    // Serialisasi — password dan token tidak bocor
    // ----------------------------------------------------------------

    public function test_password_hidden_from_serialization(): void
    {
        $user = User::factory()->create();
        $array = $user->toArray();

        $this->assertArrayNotHasKey('password', $array);
        $this->assertArrayNotHasKey('remember_token', $array);
    }

    // ----------------------------------------------------------------
    // Timestamps
    // ----------------------------------------------------------------

    public function test_user_has_timestamps(): void
    {
        $user = User::factory()->create();

        $this->assertNotNull($user->created_at);
        $this->assertNotNull($user->updated_at);
    }
}
