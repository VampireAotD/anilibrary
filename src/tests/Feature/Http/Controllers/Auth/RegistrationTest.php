<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Auth;

use App\Enums\RoleEnum;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RoleSeeder::class);
    }

    public function testRegistrationScreenCannotBeRenderedIfUserIsNotLoggedIn(): void
    {
        $response = $this->get('/register');

        $response->assertRedirectToRoute('login');
    }

    public function testRegistrationScreenCannotBeRenderedIfUserIsNotOwner(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/register');

        $response->assertForbidden();
    }

    public function testOwnerCanRegisterNewUsers(): void
    {
        $user = User::factory()->create();

        $user->assignRole(RoleEnum::OWNER->value);

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
