<?php

declare(strict_types=1);

namespace Tests\Concerns\Fake;

use App\Models\Invitation;
use Illuminate\Database\Eloquent\Collection;

trait CanCreateFakeInvitations
{
    protected function createInvitation(array $data = []): Invitation
    {
        return Invitation::factory()->create($data);
    }

    protected function createPendingInvitation(array $data = []): Invitation
    {
        return Invitation::factory()->pending()->create($data);
    }

    protected function createAcceptedInvitation(array $data = []): Invitation
    {
        return Invitation::factory()->accepted()->create($data);
    }

    protected function createDeclinedInvitation(array $data = []): Invitation
    {
        return Invitation::factory()->declined()->create($data);
    }

    /**
     * @return Collection<Invitation>
     */
    protected function createInvitationCollection(int $quantity = 1): Collection
    {
        return Invitation::factory(count: $quantity)->create();
    }

    /**
     * @return Collection<Invitation>
     */
    protected function createPendingInvitationCollection(int $quantity = 1): Collection
    {
        return Invitation::factory(count: $quantity)->pending()->create();
    }

    /**
     * @return Collection<Invitation>
     */
    protected function createAcceptedInvitationCollection(int $quantity = 1): Collection
    {
        return Invitation::factory(count: $quantity)->accepted()->create();
    }

    protected function createDeclinedInvitationCollection(int $quantity = 1): Collection
    {
        return Invitation::factory(count: $quantity)->declined()->create();
    }
}
