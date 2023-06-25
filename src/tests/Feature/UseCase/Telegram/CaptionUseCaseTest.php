<?php

declare(strict_types=1);

namespace Tests\Feature\UseCase\Telegram;

use App\DTO\UseCase\Telegram\Caption\PaginationDTO;
use App\DTO\UseCase\Telegram\Caption\ViewEncodedAnimeDTO;
use App\Enums\Telegram\Callbacks\CallbackQueryTypeEnum;
use App\Facades\Telegram\State\UserStateFacade;
use App\Models\Anime;
use App\Services\Telegram\HashService;
use App\UseCase\Telegram\CaptionUseCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;
use Tests\Traits\CanCreateFakeData;

class CaptionUseCaseTest extends TestCase
{
    use RefreshDatabase,
        WithFaker,
        CanCreateFakeData;

    private HashService    $hashService;
    private CaptionUseCase $captionUseCase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->hashService    = $this->app->make(HashService::class);
        $this->captionUseCase = $this->app->make(CaptionUseCase::class);
    }

    /**
     * @return void
     */
    public function testCannotReturnCaptionOfUnknownAnime(): void
    {
        $encoded = $this->hashService->encodeUuid(Str::orderedUuid()->toString());
        $caption = $this->captionUseCase->createDecodedAnimeCaption(
            new ViewEncodedAnimeDTO($encoded, $this->faker->randomNumber())
        );

        $this->assertEmpty($caption);
    }

    /**
     * @return void
     */
    public function testCanCreateCaptionForAddedAnime(): void
    {
        /** @var Anime $anime */
        $anime   = $this->createRandomAnimeWithRelations()->first();
        $encoded = $this->hashService->encodeUuid($anime->id);
        $caption = $this->captionUseCase->createDecodedAnimeCaption(
            new ViewEncodedAnimeDTO($encoded, $this->faker->randomNumber())
        );

        $this->assertNotEmpty($caption);
        $this->assertEquals($anime->id, $this->hashService->decodeUuid($encoded));
        $this->assertEquals($anime->caption, $caption['caption']);
        $this->assertEquals($anime->image->path, $caption['photo']);
        $this->assertNotEmpty($caption['reply_markup']['inline_keyboard']);
        $this->assertCount($anime->urls->count(), reset($caption['reply_markup']['inline_keyboard']));
        $this->assertEquals(
            $anime->urls->first()->toTelegramKeyboardButton,
            reset($caption['reply_markup']['inline_keyboard'][0])
        );
    }

    /**
     * @return void
     */
    public function testCanCreateAnimeListPaginationCaption(): void
    {
        $animeList = $this->createRandomAnimeWithRelations(3);
        $caption   = $this->captionUseCase->createPaginationCaption(
            new PaginationDTO($this->faker->randomNumber())
        );

        $first = $animeList->first();

        $this->assertInstanceOf(Anime::class, $first);
        $this->assertNotEmpty($caption);
        $this->assertEquals($first->caption, $caption['caption']);
        $this->assertEquals($first->image->path, $caption['photo']);
        $this->assertNotEmpty($caption['reply_markup']['inline_keyboard'][1]);
        $this->assertCount(1, $caption['reply_markup']['inline_keyboard'][1]);
        $this->assertEquals('>', $caption['reply_markup']['inline_keyboard'][1][0]['text']);

        $caption = $this->captionUseCase->createPaginationCaption(
            new PaginationDTO($this->faker->randomNumber(), 2)
        );
        $middle  = $animeList->offsetGet(1);

        $this->assertInstanceOf(Anime::class, $middle);
        $this->assertNotEmpty($caption);
        $this->assertEquals($middle->caption, $caption['caption']);
        $this->assertEquals($middle->image->path, $caption['photo']);
        $this->assertNotEmpty($caption['reply_markup']['inline_keyboard'][1]);
        $this->assertCount(2, $caption['reply_markup']['inline_keyboard'][1]);
        $this->assertEquals('<', $caption['reply_markup']['inline_keyboard'][1][0]['text']);
        $this->assertEquals('>', $caption['reply_markup']['inline_keyboard'][1][1]['text']);

        $caption = $this->captionUseCase->createPaginationCaption(
            new PaginationDTO($this->faker->randomNumber(), 3)
        );
        $last    = $animeList->last();

        $this->assertInstanceOf(Anime::class, $last);
        $this->assertNotEmpty($caption);
        $this->assertEquals($last->caption, $caption['caption']);
        $this->assertEquals($last->image->path, $caption['photo']);
        $this->assertNotEmpty($caption['reply_markup']['inline_keyboard'][1]);
        $this->assertCount(1, $caption['reply_markup']['inline_keyboard'][1]);
        $this->assertEquals('<', $caption['reply_markup']['inline_keyboard'][1][0]['text']);
    }

    public function testWillNotCreateSearchCaptionIfThereIsNoSearchResults(): void
    {
        UserStateFacade::shouldReceive('getSearchResult')->once()->andReturn([]);

        $caption = $this->captionUseCase->createSearchPaginationCaption(
            new PaginationDTO($this->faker->randomNumber(), queryType: CallbackQueryTypeEnum::SEARCH_LIST)
        );

        $this->assertEmpty($caption);
    }

    public function testWillCreateSearchCaptionIfThereIsSearchResults(): void
    {
        $animeList = $this->createRandomAnimeWithRelations(2);

        UserStateFacade::shouldReceive('getSearchResult')->once()->andReturn($animeList->pluck('id')->toArray());

        $caption = $this->captionUseCase->createSearchPaginationCaption(
            new PaginationDTO($this->faker->randomNumber(), queryType: CallbackQueryTypeEnum::SEARCH_LIST)
        );

        $anime = $animeList->first();

        $this->assertNotEmpty($caption);
        $this->assertEquals($anime->caption, $caption['caption']);
        $this->assertEquals($anime->image->path, $caption['photo']);
    }
}
