<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\UserAnimeList;

use App\Enums\UserAnimeList\StatusEnum;
use App\Models\Anime;
use App\Models\Pivots\UserAnimeList;
use App\Policies\AnimePolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Concerns\Fake\CanCreateFakeAnime;
use Tests\Concerns\Fake\CanCreateFakeUsers;
use Tests\TestCase;

final class UserAnimeListControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    use CanCreateFakeUsers;
    use CanCreateFakeAnime;

    public function testUserCannotAddAnimeToListThatDoesNotExist(): void
    {
        $this->actingAs($this->createUser())->post(route('anime-list.store'), ['anime_id' => $this->faker->uuid])
             ->assertSessionHasErrors('anime_id', 'Selected anime id does not exist');
    }

    public function testUserCanAddAnimeToList(): void
    {
        $user  = $this->createUser();
        $anime = $this->createAnime();

        $this->assertDatabaseEmpty(UserAnimeList::class);

        $this->actingAs($user)->post(route('anime-list.store'), ['anime_id' => $anime->id])
             ->assertRedirect()
             ->assertSessionHas('message', __('user-anime-list.added'));

        $this->assertDatabaseHas(UserAnimeList::class, [
            'user_id'  => $user->id,
            'anime_id' => $anime->id,
            'status'   => StatusEnum::PLAN_TO_WATCH,
            'rating'   => 0,
            'episodes' => 0,
        ]);
    }

    /**
     * This case is handled by the AnimePolicy.
     * @see AnimePolicy
     */
    public function testUserCannotUpdateAnimeWhichIsNotInHisList(): void
    {
        $user  = $this->createUser();
        $anime = $this->createAnime();

        $this->actingAs($user)->put(route('anime-list.update', $anime->id), ['status' => StatusEnum::DROPPED->value])
             ->assertForbidden();
    }

    public function testUserCanUpdateAnimeInList(): void
    {
        $user  = $this->createUser();
        $anime = $this->createAnime();

        $this->assertDatabaseEmpty(UserAnimeList::class);

        $user->animeList()->attach($anime, ['status' => StatusEnum::WATCHING]);

        $this->assertDatabaseHas(UserAnimeList::class, [
            'user_id'  => $user->id,
            'anime_id' => $anime->id,
            'status'   => StatusEnum::WATCHING,
        ]);

        $this->actingAs($user)->put(route('anime-list.update', $anime->id), [
            'status' => StatusEnum::COMPLETED->value,
        ])
             ->assertRedirect()
             ->assertSessionHas('message', __('user-anime-list.updated'));

        $this->assertDatabaseHas(UserAnimeList::class, [
            'user_id'  => $user->id,
            'anime_id' => $anime->id,
            'status'   => StatusEnum::COMPLETED,
        ]);
    }

    /**
     * This case is handled by the AnimePolicy.
     * @see AnimePolicy
     */
    public function testUserCannotRemoveAnimeThatIsNotInHisList(): void
    {
        $user  = $this->createUser();
        $anime = $this->createAnime();

        $this->assertDatabaseEmpty(UserAnimeList::class);

        $this->actingAs($user)->delete(route('anime-list.destroy', $anime->id))->assertForbidden();
    }

    public function testUserCanRemoveAnimeFromList(): void
    {
        $user  = $this->createUser();
        $anime = $this->createAnime();

        $this->assertDatabaseEmpty(UserAnimeList::class);

        $user->animeList()->attach($anime, ['status' => StatusEnum::WATCHING]);

        $this->assertDatabaseCount(UserAnimeList::class, 1);

        $this->actingAs($user)->delete(route('anime-list.destroy', $anime->id))
             ->assertRedirect()
             ->assertSessionHas('message', __('user-anime-list.removed'));

        $this->assertDatabaseCount(UserAnimeList::class, 0);
        $this->assertDatabaseCount(Anime::class, 1);
    }
}
