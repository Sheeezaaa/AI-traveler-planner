<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Verify login page renders correctly.
     */
    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get(route('login'));

        $response->assertStatus(200);
        $response->assertSee('Welcome Back');
    }

    /**
     * Verify active user session authentication works.
     */
    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::create([
            'name' => 'Ali Ahmed',
            'email' => 'ali@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard'));
    }

    /**
     * Verify invalid login credentials returns validation alerts.
     */
    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::create([
            'name' => 'Ali Ahmed',
            'email' => 'ali@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }
}
