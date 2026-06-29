<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Test unit untuk helper method di User model.
 */
class UserModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_is_admin_pptk_mengembalikan_true_untuk_role_admin_pptk(): void
    {
        $user = User::factory()->create(['role' => 'admin_pptk']);
        $this->assertTrue($user->isAdminPptk());
    }

    /** @test */
    public function test_is_admin_pptk_mengembalikan_false_untuk_role_manajemen(): void
    {
        $user = User::factory()->create(['role' => 'manajemen']);
        $this->assertFalse($user->isAdminPptk());
    }

    /** @test */
    public function test_is_manajemen_mengembalikan_true_untuk_role_manajemen(): void
    {
        $user = User::factory()->create(['role' => 'manajemen']);
        $this->assertTrue($user->isManajemen());
    }

    /** @test */
    public function test_is_manajemen_mengembalikan_false_untuk_role_admin_pptk(): void
    {
        $user = User::factory()->create(['role' => 'admin_pptk']);
        $this->assertFalse($user->isManajemen());
    }

    /** @test */
    public function test_is_admin_mengembalikan_true_untuk_kedua_role(): void
    {
        // isAdmin() adalah alias backward-compat yang mengizinkan admin_pptk DAN manajemen
        $admin   = User::factory()->create(['role' => 'admin_pptk']);
        $manager = User::factory()->create(['role' => 'manajemen']);

        $this->assertTrue($admin->isAdmin());
        $this->assertTrue($manager->isAdmin());
    }

    /** @test */
    public function test_is_manager_adalah_alias_dari_is_manajemen(): void
    {
        $manager = User::factory()->create(['role' => 'manajemen']);
        $admin   = User::factory()->create(['role' => 'admin_pptk']);

        $this->assertTrue($manager->isManager());
        $this->assertFalse($admin->isManager());
    }

    /** @test */
    public function test_user_memiliki_atribut_role_yang_benar(): void
    {
        $admin   = User::factory()->create(['role' => 'admin_pptk']);
        $manager = User::factory()->create(['role' => 'manajemen']);

        $this->assertEquals('admin_pptk', $admin->role);
        $this->assertEquals('manajemen', $manager->role);
    }

    /** @test */
    public function test_password_user_tidak_ditampilkan_saat_serialisasi(): void
    {
        $user = User::factory()->create();
        $json = $user->toArray();

        $this->assertArrayNotHasKey('password', $json);
        $this->assertArrayNotHasKey('remember_token', $json);
    }
}
