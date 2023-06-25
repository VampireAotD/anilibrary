<?php

declare(strict_types=1);

namespace Tests\Feature\UseCase\Telegram;

use App\DTO\UseCase\Telegram\Caption\PaginationDTO;
use App\DTO\UseCase\Telegram\Caption\ViewEncodedAnimeDTO;
use App\Enums\Telegram\Callbacks\CallbackQueryTypeEnum;
use App\Facades\Telegram\State\UserStateFacade;
use App\Services\Telegram\Base62Service;
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

    private Base62Service  $base62Service;
    private CaptionUseCase $captionUseCase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->base62Service  = $this->app->make(Base62Service::class);
        $this->captionUseCase = $this->app->make(CaptionUseCase::class);
    }

    /**
     * @return void
     */
    public function testCannotReturnCaptionOfUnknownAnime(): void
    {
        $encoded = $this->base62Service->encode(Str::orderedUuid()->toString());
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
        $anime   = $this->createRandomAnimeWithRelations()->first();
        $encoded = $this->base62Service->encode($anime->id);
        $caption = $this->captionUseCase->createDecodedAnimeCaption(
            new ViewEncodedAnimeDTO($encoded, $this->faker->randomNumber())
        );

        $this->assertNotEmpty($caption);
        $this->assertEquals($anime->id, $this->base62Service->decode($encoded));
        $this->assertEquals($anime->caption, $caption['caption']);
        $this->assertEquals($anime->image->path, $caption['photo']);
        $this->assertNotEmpty($caption['reply_markup']['inline_keyboard']);
        $this->assertCount($anime->urls->count(), reset($caption['reply_markup']['inline_keyboard']));
    }

    /**
     * @return void
     */
    public function testCanCreatePaginationCaption(): void
    {
        $animeList  = $this->createRandomAnimeWithRelations(2);
        $pagination = $this->captionUseCase->createPaginationCaption(
            new PaginationDTO($this->faker->randomNumber())
        );

        $anime = $animeList->first();

        $this->assertNotEmpty($pagination);
        $this->assertEquals($anime->caption, $pagination['caption']);
        $this->assertEquals($anime->image->path, $pagination['photo']);
        $this->assertNotEmpty($pagination['reply_markup']['inline_keyboard'][1]);
        $this->assertCount(1, $pagination['reply_markup']['inline_keyboard'][1]);
        $this->assertEquals('>', $pagination['reply_markup']['inline_keyboard'][1][0]['text']);

        $pagination = $this->captionUseCase->createPaginationCaption(
            new PaginationDTO($this->faker->randomNumber(), 2)
        );

        $anime = $animeList->last();

        $this->assertNotEmpty($pagination);
        $this->assertEquals($anime->caption, $pagination['caption']);
        $this->assertEquals($anime->image->path, $pagination['photo']);
        $this->assertNotEmpty($pagination['reply_markup']['inline_keyboard'][1]);
        $this->assertCount(1, $pagination['reply_markup']['inline_keyboard'][1]);
        $this->assertEquals('<', $pagination['reply_markup']['inline_keyboard'][1][0]['text']);
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
