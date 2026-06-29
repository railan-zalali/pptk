<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_halaman_profil_dapat_ditampilkan(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/profile')->assertOk();
    }

    /** @test */
    public function test_informasi_profil_dapat_diperbarui(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->patch('/profile', [
                'name'  => 'Nama Diperbarui',
                'email' => 'baru@example.com',
            ])
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $user->refresh();
        $this->assertSame('Nama Diperbarui', $user->name);
        $this->assertSame('baru@example.com', $user->email);
        $this->assertNull($user->email_verified_at); // email baru → belum terverifikasi
    }

    /** @test */
    public function test_status_verifikasi_email_tidak_berubah_jika_email_sama(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->patch('/profile', [
                'name'  => 'Nama Baru',
                'email' => $user->email, // email tidak berubah
            ])
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $this->assertNotNull($user->refresh()->email_verified_at);
    }

    /** @test */
    public function test_akun_dapat_dihapus_dengan_password_benar(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->delete('/profile', ['password' => 'password'])
            ->assertSessionHasNoErrors()
            ->assertRedirect('/');

        $this->assertGuest();
        $this->assertNull($user->fresh());
    }

    /** @test */
    public function test_penghapusan_akun_gagal_dengan_password_salah(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->from('/profile')
            ->delete('/profile', ['password' => 'salah-password'])
            ->assertSessionHasErrorsIn('userDeletion', 'password')
            ->assertRedirect('/profile');

        $this->assertNotNull($user->fresh());
    }
}
