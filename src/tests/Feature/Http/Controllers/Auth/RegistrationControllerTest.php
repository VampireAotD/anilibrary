<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Auth;

use App\Http\Middleware\Registration\HasInvitationMiddleware;
use App\Models\Invitation;
use App\Notifications\Auth\VerifyEmailNotification;
use App\Services\Url\SignedUrlService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Tests\Concerns\Fake\CanCreateFakeInvitations;
use Tests\Concerns\Fake\CanCreateFakeUsers;
use Tests\TestCase;

class RegistrationControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    use CanCreateFakeUsers;
    use CanCreateFakeInvitations;

    private SignedUrlService $signedUrlService;

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->signedUrlService = $this->app->make(SignedUrlService::class);
    }

    /**
     * This case is handled by 'signed' middleware.
     */
    public function testRegisterScreenCannotBeRenderedIfThereIsNoSignature(): void
    {
        $this->get(route('register'))->assertForbidden();
    }

    /**
     * This case is handled by 'signed' middleware.
     */
    public function testRegistrationScreenCannotBeRenderedIfSignatureIsInvalid(): void
    {
        $this->get(route('register', [
            'expires'   => now()->unix(),
            'signature' => Str::random(),
        ]))->assertForbidden();
    }

    /**
     * This case is handled by 'signed' middleware.
     */
    public function testRegistrationScreenCannotBeRenderedIfUrlIsExpired(): void
    {
        $invitation = $this->createAcceptedInvitation();

        $url = $this->signedUrlService->createRegistrationLink($invitation->id);

        $this->travel(config('auth.registration.expire') + 1)->minutes();

        $this->get($url)->assertForbidden()->assertSee('Invalid signature');
    }

    /**
     * This case is handled by 'registration.has_invitation' middleware.
     * @see HasInvitationMiddleware
     */
    public function testRegistrationScreenCannotBeRenderedIfUserDoesNotHaveInvitation(): void
    {
        $url = $this->signedUrlService->createRegistrationLink(Str::random());

        $this->get($url)->assertForbidden()->assertSee(__('auth.middleware.invalid_invitation'));
    }

    /**
     * This case is handled by 'registration.has_invitation' middleware.
     * @see HasInvitationMiddleware
     */
    public function testRegistrationScreenCannotBeRenderedIfInvitationIsNotAccepted(): void
    {
        $invitations = [$this->createPendingInvitation(), $this->createDeclinedInvitation()];

        foreach ($invitations as $invitation) {
            $url = $this->signedUrlService->createRegistrationLink($invitation->id);

            $this->get($url)->assertForbidden()->assertSee(__('auth.middleware.invalid_invitation'));
        }
    }

    public function testRegistrationScreenCanBeRendered(): void
    {
        $invitation = $this->createAcceptedInvitation();

        $url = $this->signedUrlService->createRegistrationLink($invitation->id);

        $this->get($url)->assertOk();
    }

    public function testUserCannotRegisterWithoutInvitation(): void
    {
        $this->post(route('register'), [
            'name'                  => $this->faker->name,
            'email'                 => $this->faker->unique()->safeEmail,
            'password'              => $password = Str::random(),
            'password_confirmation' => $password,
        ])->assertSessionHasErrors('email');
    }

    public function testUserCannotRegisterIfEmailIsAlreadyRegistered(): void
    {
        $user = $this->createUser();

        $this->post(route('register'), [
            'name'                  => $this->faker->name,
            'email'                 => $user->email,
            'password'              => $password = Str::random(),
            'password_confirmation' => $password,
        ])->assertSessionHasErrors('email');
    }

    public function testUserCannotRegisterTwoTimesWithSameLink(): void
    {
        Notification::fake();

        $invitation = $this->createAcceptedInvitation();

        $url = $this->signedUrlService->createRegistrationLink($invitation->id);

        $this->get($url)->assertOk();

        $response = $this->post(route('register'), [
            'name'                  => $this->faker->name,
            'email'                 => $invitation->email,
            'password'              => $password = Str::random(),
            'password_confirmation' => $password,
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
        $this->assertDatabaseMissing(Invitation::class, $invitation->toArray());

        Notification::assertCount(1);
        Notification::assertSentTo(auth()->user(), VerifyEmailNotification::class);

        Auth::logout();
        $this->assertFalse(Auth::check());

        $this->get($url)->assertForbidden();
    }

    public function testUserCanRegister(): void
    {
        Notification::fake();

        $invitation = $this->createAcceptedInvitation();

        $response = $this->post(route('register'), [
            'name'                  => $this->faker->name,
            'email'                 => $invitation->email,
            'password'              => $password = Str::random(),
            'password_confirmation' => $password,
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
        $this->assertDatabaseMissing(Invitation::class, $invitation->toArray());

        Notification::assertCount(1);
        Notification::assertSentTo(auth()->user(), VerifyEmailNotification::class);
    }
}
