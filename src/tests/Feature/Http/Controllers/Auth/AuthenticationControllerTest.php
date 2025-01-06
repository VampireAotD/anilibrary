<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\Fake\CanCreateFakeUsers;
use Tests\TestCase;

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

        $response = $this->post('/login', [
            'email'    => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function testUsersCannotAuthenticateWithInvalidPassword(): void
    {
        $user = $this->createUser();

        $this->post('/login', [
            'email'    => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function testUsersCanLogout(): void
    {
        $user = $this->createUser();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }
}
