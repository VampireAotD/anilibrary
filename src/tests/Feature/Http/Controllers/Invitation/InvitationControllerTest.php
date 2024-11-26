<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Invitation;

use App\Enums\Invitation\StatusEnum;
use App\Http\Middleware\Invitation\IsPendingInvitationMiddleware;
use App\Http\Middleware\Invitation\NotDeclinedInvitationMiddleware;
use App\Mail\Invitation\AcceptedInvitationMail;
use App\Mail\Invitation\DeclinedInvitationMail;
use App\Models\Invitation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Tests\Concerns\Fake\CanCreateFakeInvitations;
use Tests\Concerns\Fake\CanCreateFakeUsers;
use Tests\TestCase;

class InvitationControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    use CanCreateFakeUsers;
    use CanCreateFakeInvitations;

    public function testCannotInteractWithInvitationScreenIfUserIsNotVerified(): void
    {
        $this->actingAs($this->createUnverifiedUser())
             ->get(route('invitation.index'))
             ->assertRedirectToRoute('verification.notice');
    }

    public function testInvitationScreenCannotBeRenderedIfUserIsNotOwner(): void
    {
        $this->actingAs($this->createUser())->get(route('invitation.index'))->assertForbidden();
    }

    public function testInvitationScreenWillBeRenderedIfUserIsOwner(): void
    {
        $this->actingAs($this->createOwner())->get(route('invitation.index'))->assertOk();
    }

    public function testUserCannotSendInvitationIfHeIsNotOwner(): void
    {
        $this->actingAs($this->createUser())
             ->post(route('invitation.send', ['email' => $this->faker->email]))
             ->assertForbidden();
    }

    public function testOwnerCanInviteNewUsers(): void
    {
        Mail::fake();

        $this->assertDatabaseEmpty(Invitation::class);

        $email = $this->faker->email;

        $this->actingAs($this->createOwner())
             ->post(route('invitation.send', ['email' => $email]))
             ->assertRedirect()
             ->assertSessionHas('message', __('invitation.sent'));

        $this->assertDatabaseHas(Invitation::class, ['email' => $email, 'status' => StatusEnum::ACCEPTED]);

        Mail::assertQueued(AcceptedInvitationMail::class);
    }

    /**
     * This case is handled by 'invitation.is_pending' middleware.
     * @see IsPendingInvitationMiddleware
     */
    public function testInvitationCannotBeAcceptedIfItIsNotInPendingStatus(): void
    {
        $invitation = $this->createAcceptedInvitation();

        $this->withoutExceptionHandling();

        $this->expectExceptionMessage(__('invitation.cannot_be_accepted'));

        $this->actingAs($this->createOwner())
             ->put(route('invitation.accept', ['invitation' => $invitation]))
             ->assertBadRequest();
    }

    public function testInvitationCanBeAccepted(): void
    {
        Mail::fake();

        $invitation = $this->createPendingInvitation();

        $this->actingAs($this->createOwner())
             ->put(route('invitation.accept', ['invitation' => $invitation]))
             ->assertRedirect()
             ->assertSessionHas('message', __('invitation.accepted'));

        $this->assertEquals(StatusEnum::ACCEPTED, $invitation->refresh()->status);
        Mail::assertQueued(AcceptedInvitationMail::class);
    }

    /**
     * This case is handled by 'invitation.not_declined' middleware.
     * @see NotDeclinedInvitationMiddleware
     */
    public function testInvitationCannotBeDeclinedMultipleTimes(): void
    {
        $invitation = $this->createDeclinedInvitation();

        $this->withoutExceptionHandling();

        $this->expectExceptionMessage(__('invitation.already_declined'));

        $this->actingAs($this->createOwner())
             ->delete(route('invitation.decline', ['invitation' => $invitation]))
             ->assertBadRequest();
    }

    public function testInvitationCanBeDeclined(): void
    {
        Mail::fake();

        $invitation = $this->createPendingInvitation();

        $this->actingAs($this->createOwner())
             ->delete(route('invitation.decline', ['invitation' => $invitation]))
             ->assertRedirect()
             ->assertSessionHas('message', __('invitation.declined'));

        Mail::assertQueued(
            DeclinedInvitationMail::class,
            static fn(DeclinedInvitationMail $mail) => $mail->hasTo($invitation->email)
        );
    }
}
