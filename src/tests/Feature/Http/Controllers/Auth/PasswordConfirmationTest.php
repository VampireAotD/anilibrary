<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\Fake\CanCreateFakeUsers;

class PasswordConfirmationTest extends TestCase
{
    use RefreshDatabase;
    use CanCreateFakeUsers;

    public function testConfirmPasswordScreenCanBeRendered(): void
    {
        $this->actingAs($this->createUser())->get('/confirm-password')->assertOk();
    }

    public function testPasswordCanBeConfirmed(): void
    {
        $response = $this->actingAs($this->createUser())->post('/confirm-password', [
            'password' => 'password',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();
    }

    public function testPasswordIsNotConfirmedWithInvalidPassword(): void
    {
        $response = $this->actingAs($this->createUser())->post('/confirm-password', [
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors();
    }
}
