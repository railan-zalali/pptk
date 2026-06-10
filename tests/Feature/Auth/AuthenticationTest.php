<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_admin_can_authenticate_and_redirects_to_admin_dashboard(): void
    {
        $user = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/admin');
    }

    public function test_regular_user_can_authenticate_and_redirects_to_user_dashboard(): void
    {
        $user = User::factory()->create([
            'role' => 'viewer',
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/dashboard');
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }

    public function test_admin_with_user_dashboard_intended_url_is_redirected_to_admin_dashboard(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        // Set intended URL to user dashboard
        session(['url.intended' => url('/dashboard')]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
    }

    public function test_admin_with_admin_path_intended_url_is_redirected_to_intended_url(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        // Set intended URL to an admin sub-page
        session(['url.intended' => url('/admin/gardens')]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(url('/admin/gardens'));
    }

    public function test_regular_user_with_admin_intended_url_is_redirected_to_user_dashboard(): void
    {
        $user = User::factory()->create(['role' => 'viewer']);

        // Set intended URL to admin dashboard
        session(['url.intended' => url('/admin')]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('dashboard'));
    }

    public function test_regular_user_with_safe_intended_url_is_redirected_to_intended_url(): void
    {
        $user = User::factory()->create(['role' => 'viewer']);

        // Set intended URL to profile edit page
        session(['url.intended' => url('/profile')]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(url('/profile'));
    }
}

