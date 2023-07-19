<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Auth;

use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\Fake\CanCreateFakeUsers;

class AuthenticationControllerTest extends TestCase
{
    use RefreshDatabase;
    use CanCreateFakeUsers;

    public function testLoginScreenCanBeRendered(): void
    {
        $response = $this->get('/login');

        $response->assertOk();
    }

    public function testUsersCanAuthenticateUsingTheLoginScreen(): void
    {
        $user = $this->createUser();

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
        $user = $this->createUser();

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
