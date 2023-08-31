<?php

declare(strict_types=1);

namespace Tests\Feature\GraphQL;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Traits\Fake\CanCreateFakeAnime;
use Tests\Traits\Fake\CanCreateFakeUsers;

class EndpointAccessTest extends GraphQLTestCase
{
    use RefreshDatabase;
    use CanCreateFakeUsers;
    use CanCreateFakeAnime;

    public function testEndpointCannotBeAccessedByUnauthorizedUsers(): void
    {
        $anime = $this->createAnimeWithRelations();

        $this->query('Anime', ['id' => $anime->id], ['title'])->assertUnauthorized();
    }

    public function testEndpointCanBeAccessedByAuthorizedUsers(): void
    {
        $anime = $this->createAnimeWithRelations();

        $this->actingAs($this->createUser())->query('Anime', ['id' => $anime->id], ['title'])
             ->assertJsonFragment(['title' => $anime->title]);
    }
}
