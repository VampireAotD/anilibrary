<?php

declare(strict_types=1);

namespace Tests\Feature\GraphQL\Queries\Anime;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\GraphQL\GraphQLTestCase;
use Tests\Traits\Fake\CanCreateFakeAnime;
use Tests\Traits\Fake\CanCreateFakeUsers;

class AnimeQueryTest extends GraphQLTestCase
{
    use RefreshDatabase;
    use CanCreateFakeAnime;
    use CanCreateFakeUsers;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs($this->createUser());
    }

    public function testQueryWillNotWorkWithoutIdInArguments(): void
    {
        $this->query('Anime', [], ['title'])->assertJsonFragment([
            'message' => 'Field "Anime" argument "id" of type "ID!" is required but not provided.',
        ]);
    }

    public function testQueryCanFindAndReturnAnimeById(): void
    {
        $anime = $this->createAnime();

        $this->query('Anime', ['id' => $anime->id], ['title'])->assertJsonFragment([
            'title' => $anime->title,
        ]);
    }
}
