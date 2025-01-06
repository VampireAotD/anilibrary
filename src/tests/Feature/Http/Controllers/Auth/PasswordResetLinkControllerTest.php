<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Auth;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\Concerns\Fake\CanCreateFakeUsers;
use Tests\TestCase;

class PasswordResetLinkControllerTest extends TestCase
{
    use RefreshDatabase;
    use CanCreateFakeUsers;

    public function testResetPasswordLinkScreenCanBeRendered(): void
    {
        $this->get(route('password.request'))->assertOk();
    }

    public function testResetPasswordLinkCanBeRequested(): void
    {
        Notification::fake();

        $user = $this->createUser();

        $this->post(route('password.email'), ['email' => $user->email]);

        Notification::assertSentTo($user, ResetPassword::class);
    }

    public function testResetPasswordScreenCanBeRendered(): void
    {
        Notification::fake();

        $user = $this->createUser();

        $this->post(route('password.email'), ['email' => $user->email]);

        Notification::assertSentTo($user, ResetPassword::class, function ($notification) {
            $response = $this->get(route('password.reset', [$notification->token]));

            $response->assertOk();

            return true;
        });
    }

    public function testPasswordCanBeResetWithValidToken(): void
    {
        Notification::fake();

        $user = $this->createUser();

        $this->post(route('password.email'), ['email' => $user->email]);

        Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user) {
            $response = $this->post(route('password.store'), [
                'token'                 => $notification->token,
                'email'                 => $user->email,
                'password'              => 'password',
                'password_confirmation' => 'password',
            ]);

            $response->assertSessionHasNoErrors()->assertRedirect(route('login'));

            return true;
        });
    }
}
