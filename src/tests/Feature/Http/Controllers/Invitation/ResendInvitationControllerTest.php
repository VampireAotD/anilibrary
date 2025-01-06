<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Invitation;

use App\Helpers\Registration;
use App\Mail\Invitation\AcceptedInvitationMail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\Concerns\Fake\CanCreateFakeInvitations;
use Tests\Concerns\Fake\CanCreateFakeUsers;
use Tests\TestCase;

final class ResendInvitationControllerTest extends TestCase
{
    use RefreshDatabase;
    use CanCreateFakeUsers;
    use CanCreateFakeInvitations;

    /**
     * This case is handled by 'role' middleware
     */
    public function testOnlyOwnerCanResendInvitations(): void
    {
        $this->actingAs($this->createUser())
             ->post(route('invitation.resend', $this->createInvitation()))
             ->assertForbidden();
    }

    /**
     * This case is handled by 'invitation.accepted' middleware
     * @see AcceptedInvitationMail
     */
    public function testCannotResendInvitationIfItIsNotAccepted(): void
    {
        $invitation = $this->createPendingInvitation();

        $this->withoutExceptionHandling();

        $this->expectExceptionMessage(__('invitation.invalid_status'));

        $this->actingAs($this->createOwner())
             ->post(route('invitation.resend', $invitation))
             ->assertForbidden();
    }

    public function testCanResendInvitation(): void
    {
        Mail::fake();

        $invitation = $this->createAcceptedInvitation();

        $this->actingAs($this->createOwner())
             ->post(route('invitation.resend', $invitation))
             ->assertRedirect()
             ->assertSessionHas('message', __('invitation.sent'));

        $invitation->refresh();

        $this->assertEquals(Registration::expirationDate()->timestamp, $invitation->expires_at->timestamp);
        Mail::assertQueued(AcceptedInvitationMail::class);
    }

    /**
     * This case is handled by 'throttle' middleware
     */
    public function testCanOnlyResendInvitationOnceInAMinute(): void
    {
        Mail::fake();

        $owner      = $this->createOwner();
        $invitation = $this->createAcceptedInvitation();

        $this->actingAs($owner)
             ->post(route('invitation.resend', $invitation))
             ->assertRedirect()
             ->assertSessionHas('message', __('invitation.sent'));

        Mail::assertQueued(AcceptedInvitationMail::class);

        $this->actingAs($owner)
             ->post(route('invitation.resend', $invitation))
             ->assertTooManyRequests();

        $this->travel(1)->minute();

        $this->actingAs($owner)
             ->post(route('invitation.resend', $invitation))
             ->assertRedirect()
             ->assertSessionHas('message', __('invitation.sent'));

        Mail::assertQueued(AcceptedInvitationMail::class);
    }
}
