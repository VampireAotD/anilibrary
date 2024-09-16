<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Invitation;

use App\Mail\Invitation\InvitationMail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Tests\Concerns\Fake\CanCreateFakeUsers;
use Tests\TestCase;

class InvitationControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    use CanCreateFakeUsers;

    public function testCannotInteractWithInvitationScreenIfUserIsNotVerified(): void
    {
        $this->actingAs($this->createUnverifiedUser())
             ->get(route('invitation.create'))
             ->assertRedirectToRoute('verification.notice');
    }

    public function testInvitationScreenCannotBeRenderedIfUserIsNotOwner(): void
    {
        $this->actingAs($this->createUser())->get(route('invitation.create'))->assertForbidden();
    }

    public function testInvitationScreenWillBeRenderedIfUserIsOwner(): void
    {
        $this->actingAs($this->createOwner())->get(route('invitation.create'))->assertOk();
    }

    public function testUserCannotSendInvitationIfHeIsNotOwner(): void
    {
        $this->actingAs($this->createUser())
             ->post(route('invitation.send', ['email' => $this->faker->email]))
             ->assertForbidden();
    }

    public function testOwnerCanSendInviteNewUsers(): void
    {
        Mail::fake();
        Cache::shouldReceive('add')->once()->andReturnTrue();

        $this->actingAs($this->createOwner())
             ->post(route('invitation.send', ['email' => $this->faker->email]))
             ->assertRedirectToRoute('invitation.create');

        Mail::assertQueued(InvitationMail::class);
    }
}
