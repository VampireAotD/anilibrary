<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Anime;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Concerns\Fake\CanCreateFakeAnime;
use Tests\Concerns\Fake\CanCreateFakeUsers;
use Tests\TestCase;

final class RandomAnimeControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    use CanCreateFakeUsers;
    use CanCreateFakeAnime;

    public function testCanPickRandomAnime(): void
    {
        $this->createAnimeCollectionWithRelations(quantity: 5);

        $response = $this->actingAs($this->createUser())->get(route('anime.random'));

        $animeId = last(explode('/', $response->headers->get('Location')));

        $response->assertRedirectToRoute('anime.show', $animeId);
    }
}
