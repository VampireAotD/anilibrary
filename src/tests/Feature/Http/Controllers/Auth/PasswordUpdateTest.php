<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Tests\Traits\Fake\CanCreateFakeUsers;

class PasswordUpdateTest extends TestCase
{
    use RefreshDatabase;
    use CanCreateFakeUsers;

    public function testPasswordCanBeUpdated(): void
    {
        $user = $this->createUser();

        $response = $this
            ->actingAs($user)
            ->from('/profile')
            ->put('/password', [
                'current_password'      => 'password',
                'password'              => 'new-password',
                'password_confirmation' => 'new-password',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $this->assertTrue(Hash::check('new-password', $user->refresh()->password));
    }

    public function testCorrectPasswordMustBeProvidedToUpdatePassword(): void
    {
        $user = $this->createUser();

        $response = $this
            ->actingAs($user)
            ->from('/profile')
            ->put('/password', [
                'current_password'      => 'wrong-password',
                'password'              => 'new-password',
                'password_confirmation' => 'new-password',
            ]);

        $response
            ->assertSessionHasErrors('current_password')
            ->assertRedirect('/profile');
    }
}
