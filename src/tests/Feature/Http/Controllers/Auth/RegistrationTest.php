<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Auth;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function testRegistrationScreenCannotBeRenderedIfUserIsNotLoggedIn(): void
    {
        $response = $this->get('/register');

        $response->assertRedirectToRoute('login');
    }

    public function testOwnerCanRegisterNewUsers(): void
    {
        // TODO add roles and make so that only owner can register new users
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(
            '/register',
            [
                'name'                  => 'Test User',
                'email'                 => 'test@example.com',
                'password'              => 'password',
                'password_confirmation' => 'password',
            ]
        );

        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);
    }
}
