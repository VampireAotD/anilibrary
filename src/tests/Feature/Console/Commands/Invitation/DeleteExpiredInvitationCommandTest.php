<?php

declare(strict_types=1);

namespace Tests\Feature\Console\Commands\Invitation;

use App\Console\Commands\Invitation\DeleteExpiredInvitationCommand;
use App\Models\Invitation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\Fake\CanCreateFakeInvitations;
use Tests\TestCase;

final class DeleteExpiredInvitationCommandTest extends TestCase
{
    use RefreshDatabase;
    use CanCreateFakeInvitations;

    public function testCanOnlyDeleteExpiredInvitations(): void
    {
        $this->createPendingInvitationCollection(quantity: 5);
        $this->createAcceptedInvitationCollection(quantity: 5);
        $this->createDeclinedInvitationCollection(quantity: 5);

        $this->assertDatabaseCount(Invitation::class, 15);

        $this->travel(config('auth.registration.expire'))->minutes();

        $this->artisan(DeleteExpiredInvitationCommand::class)->assertOk();

        $this->assertDatabaseCount(Invitation::class, 10);
    }

    public function testCanDeleteExpiredInvitationById(): void
    {
        $invitation = $this->createAcceptedInvitation();

        $this->travel(config('auth.registration.expire'))->minutes();

        $this->artisan(DeleteExpiredInvitationCommand::class, ['--id' => $invitation->id])->assertOk();

        $this->assertDatabaseCount(Invitation::class, 0);
    }
}
