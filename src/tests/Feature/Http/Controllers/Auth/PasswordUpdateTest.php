<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Auth;

use App\Events\User\PasswordChangedEvent;
use App\Listeners\User\PasswordChangedListener;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Tests\Concerns\Fake\CanCreateFakeUsers;
use Tests\TestCase;

class PasswordUpdateTest extends TestCase
{
    use RefreshDatabase;
    use CanCreateFakeUsers;

    public function testPasswordCanBeUpdated(): void
    {
        Event::fake();

        $user = $this->createUser();

        $response = $this
            ->actingAs($user)
            ->from(route('profile.edit'))
            ->put(route('password.update'), [
                'current_password'      => 'password',
                'password'              => 'new-password',
                'password_confirmation' => 'new-password',
            ]);

        Event::assertDispatched(
            PasswordChangedEvent::class,
            fn(PasswordChangedEvent $event) => $event->user->is($user)
        );
        Event::assertListening(PasswordChangedEvent::class, PasswordChangedListener::class);

        $response->assertSessionHasNoErrors()->assertRedirect('/profile');

        $this->assertTrue(Hash::check('new-password', $user->refresh()->password));
    }

    public function testCorrectPasswordMustBeProvidedToUpdatePassword(): void
    {
        Event::fake();

        $user = $this->createUser();

        $response = $this
            ->actingAs($user)
            ->from(route('profile.edit'))
            ->put(route('password.update'), [
                'current_password'      => 'wrong-password',
                'password'              => 'new-password',
                'password_confirmation' => 'new-password',
            ]);

        Event::assertNotDispatched(PasswordChangedEvent::class);

        $response->assertSessionHasErrors('current_password')->assertRedirect(route('profile.edit'));
    }
}
