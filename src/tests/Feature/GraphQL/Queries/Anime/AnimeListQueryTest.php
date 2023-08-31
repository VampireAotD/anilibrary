<?php

declare(strict_types=1);

namespace Tests\Feature\GraphQL\Queries\Anime;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\GraphQL\GraphQLTestCase;
use Tests\Traits\Fake\CanCreateFakeAnime;
use Tests\Traits\Fake\CanCreateFakeUsers;

class AnimeListQueryTest extends GraphQLTestCase
{
    use RefreshDatabase;
    use CanCreateFakeAnime;
    use CanCreateFakeUsers;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs($this->createUser());
    }

    public function testQueryListCanChangePage(): void
    {
        $this->createAnimeCollectionWithRelations(10);

        $this->query('AnimeList', ['page' => 2], ['current_page'])->assertJsonFragment([
            'current_page' => 2,
        ]);
    }

    public function testQueryListCanChangeItemsPerPage(): void
    {
        $this->createAnimeCollectionWithRelations(10);

        $this->query('AnimeList', ['perPage' => 2], ['per_page'])->assertJsonFragment([
            'per_page' => 2,
        ]);
    }
}
