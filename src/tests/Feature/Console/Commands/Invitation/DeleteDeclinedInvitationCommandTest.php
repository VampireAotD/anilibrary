<?php

declare(strict_types=1);

namespace Tests\Feature\Console\Commands\Invitation;

use App\Console\Commands\Invitation\DeleteDeclinedInvitationCommand;
use App\Models\Invitation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\Fake\CanCreateFakeInvitations;
use Tests\TestCase;

final class DeleteDeclinedInvitationCommandTest extends TestCase
{
    use RefreshDatabase;
    use CanCreateFakeInvitations;

    public function testWillDeleteDeclinedInvitationsOnlyAfterAYear(): void
    {
        $this->createDeclinedInvitation();

        $this->travel(1)->year();

        $this->createDeclinedInvitationCollection(quantity: 4);

        $this->assertDatabaseCount(Invitation::class, 5);

        $this->artisan(DeleteDeclinedInvitationCommand::class)->assertOk();

        $this->assertDatabaseCount(Invitation::class, 4);
    }

    public function testCanOnlyDeleteDeclinedInvitations(): void
    {
        $this->createPendingInvitationCollection(quantity: 5);
        $this->createAcceptedInvitationCollection(quantity: 5);
        $this->createDeclinedInvitationCollection(quantity: 5);

        $this->assertDatabaseCount(Invitation::class, 15);

        $this->travel(1)->year();

        $this->artisan(DeleteDeclinedInvitationCommand::class)->assertOk();

        $this->assertDatabaseCount(Invitation::class, 10);
    }

    public function testCanDeleteDeclinedInvitationById(): void
    {
        $invitation = $this->createDeclinedInvitation();

        $this->travel(1)->year();

        $this->artisan(DeleteDeclinedInvitationCommand::class, ['--id' => $invitation->id])->assertOk();

        $this->assertDatabaseCount(Invitation::class, 0);
    }
}
