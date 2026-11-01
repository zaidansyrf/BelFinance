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
        $this->get('/')->assertStatus(200);
        $this->get('/login')->assertStatus(200);
    }

    public function test_keuangan_user_is_redirected_to_dashboard_after_login(): void
    {
        $user = User::factory()->keuangan()->create();

        $response = $this->post('/login', [
            'name' => $user->name,
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('dashboard'));
    }

    public function test_owner_user_is_redirected_to_beranda_after_login(): void
    {
        $user = User::factory()->owner()->create();

        $response = $this->post('/login', [
            'name' => $user->name,
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('owner-beranda'));
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'name' => $user->name,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_logged_in_users_visiting_login_are_redirected_by_role(): void
    {
        $this->actingAs(User::factory()->owner()->create())
            ->get('/login')
            ->assertRedirect(route('owner-beranda'));
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect(route('login-belfinance'));
    }

    public function test_public_registration_is_disabled(): void
    {
        $this->get('/register')->assertNotFound();

        $this->post('/register', [
            'name' => 'penyusup',
            'email' => 'penyusup@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertStatus(405);

        $this->assertDatabaseMissing('users', ['name' => 'penyusup']);
    }
}
