<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Invitation;

use App\Mail\Invitation\InvitationMail;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;
use Tests\Traits\Fake\CanCreateFakeUsers;

class InvitationControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    use CanCreateFakeUsers;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RoleSeeder::class);
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

        $this->actingAs($this->createOwner())
             ->post(route('invitation.send', ['email' => $this->faker->email]))
             ->assertRedirectToRoute('invitation.create');

        Mail::assertQueued(InvitationMail::class);
    }
}
