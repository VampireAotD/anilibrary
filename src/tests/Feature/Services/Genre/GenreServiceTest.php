<?php

declare(strict_types=1);

namespace Tests\Feature\Services\Genre;

use App\Models\Genre;
use App\Services\Genre\GenreService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GenreServiceTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    private GenreService $genreService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->genreService = $this->app->make(GenreService::class);
    }

    public function testWillNotCreateOrUpdateNewGenresIfNoGenresAreProvided(): void
    {
        $this->assertDatabaseCount(Genre::class, 0);

        $this->genreService->sync([]);
        $this->assertDatabaseCount(Genre::class, 0);
    }

    public function testCanCreateNewGenres(): void
    {
        $this->assertDatabaseCount(Genre::class, 0);

        $genres = Genre::factory(10)->make()->pluck('name')->map(
            fn(string $genre) => ['name' => $genre]
        );

        $this->genreService->sync($genres->toArray());
        $this->assertDatabaseCount(Genre::class, 10);
    }

    public function testCanUpsertGenres(): void
    {
        $genres = Genre::factory(10)->create();
        $this->assertDatabaseCount(Genre::class, 10);

        $newGenres = $genres->pluck('name')->merge($this->faker->words(5))->map(
            fn(string $genre) => ['name' => $genre]
        );

        $this->genreService->sync($newGenres->toArray());
        $this->assertDatabaseHas(Genre::class, ['name' => $newGenres->toArray()]);
    }
}
