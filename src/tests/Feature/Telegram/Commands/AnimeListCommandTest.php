<?php

declare(strict_types=1);

namespace Tests\Feature\Telegram\Commands;

use App\DTO\Factory\Telegram\CallbackData\PaginationCallbackDataDTO;
use App\Enums\Telegram\Actions\CommandEnum;
use App\Factory\Telegram\CallbackData\CallbackDataFactory;
use App\Models\Anime;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Traits\CanCreateMocks;
use Tests\Traits\Fake\CanCreateFakeAnime;

class AnimeListCommandTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    use CanCreateMocks;
    use CanCreateFakeAnime;

    private CallbackDataFactory $callbackDataFactory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpFakeBot();
        $this->callbackDataFactory = $this->app->make(CallbackDataFactory::class);
    }

    public function testCanGenerateAnimeList(): void
    {
        $animeList = $this->createAnimeCollectionWithRelations(2);

        $firstPageData = $animeList->first();
        $lastPageData  = $animeList->last();

        $this->assertInstanceOf(Anime::class, $firstPageData);
        $this->assertInstanceOf(Anime::class, $lastPageData);

        $secondPageCallback = $this->callbackDataFactory->resolve(new PaginationCallbackDataDTO(2));

        // After pressing button or using command bot must render anime with pagination
        // After pressing pagination button callback must render next page
        $this->bot->hearText(CommandEnum::ANIME_LIST_COMMAND->value)
                  ->reply()
                  ->assertReplyMessage([
                      'photo'   => $firstPageData->image->path,
                      'caption' => $firstPageData->to_telegram_caption,
                  ])
                  ->hearCallbackQueryData($secondPageCallback)
                  ->reply()
                  ->assertReplyMessage([
                      'media' => json_encode([
                          'type'        => 'photo',
                          'media'       => $lastPageData->image->path,
                          'caption'     => $lastPageData->to_telegram_caption,
                          'has_spoiler' => false,
                      ]),
                  ]);
    }
}
