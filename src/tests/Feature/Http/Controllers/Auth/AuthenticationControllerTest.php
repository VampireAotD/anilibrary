<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Auth;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testLoginScreenCanBeRendered(): void
    {
        $response = $this->get('/login');

        $response->assertOk();
    }

    public function testUsersCanAuthenticateUsingTheLoginScreen(): void
    {
        $user = User::factory()->create();

        $response = $this->post(
            '/login',
            [
                'email'    => $user->email,
                'password' => 'password',
            ]
        );

        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);
    }

    public function testUsersCannotAuthenticateWithInvalidPassword(): void
    {
        $user = User::factory()->create();

        $this->post(
            '/login',
            [
                'email'    => $user->email,
                'password' => 'wrong-password',
            ]
        );

        $this->assertGuest();
    }
}
