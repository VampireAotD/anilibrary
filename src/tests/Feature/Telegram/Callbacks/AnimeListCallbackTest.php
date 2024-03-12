<?php

declare(strict_types=1);

namespace Tests\Feature\Telegram\Callbacks;

use App\DTO\Factory\Telegram\CallbackData\PaginationCallbackDataDTO;
use App\Factory\Telegram\CallbackData\CallbackDataFactory;
use App\Models\Anime;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\CanCreateMocks;
use Tests\Traits\Fake\CanCreateFakeAnime;

class AnimeListCallbackTest extends TestCase
{
    use RefreshDatabase;
    use CanCreateMocks;
    use CanCreateFakeAnime;

    private CallbackDataFactory $callbackDataFactory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpFakeBot();
        $this->callbackDataFactory = $this->app->make(CallbackDataFactory::class);
    }

    public function testCallbackWillNotRenderAnimeListIfDatabaseIsEmpty(): void
    {
        $callbackData = $this->callbackDataFactory->resolve(new PaginationCallbackDataDTO());

        $this->bot->hearCallbackQueryData($callbackData)
                  ->reply()
                  ->assertReplyMessage(['text' => __('telegram.callbacks.anime_list.render_error')]);
    }

    public function testCallbackWillRenderAnimeList(): void
    {
        $anime        = $this->createAnimeWithRelations();
        $callbackData = $this->callbackDataFactory->resolve(new PaginationCallbackDataDTO());

        $reply = [
            'type'        => 'photo',
            'media'       => $anime->image->path,
            'caption'     => $anime->toTelegramCaption,
            'has_spoiler' => false,
        ];

        $this->bot->hearCallbackQueryData($callbackData)
                  ->reply()
                  ->assertReplyMessage([
                      'media' => json_encode($reply),
                  ]);
    }

    public function testCallbackWillRenderAnimeListWithPagination(): void
    {
        $animeCollection = $this->createAnimeCollectionWithRelations(quantity: 2);
        $firstPage       = $this->callbackDataFactory->resolve(new PaginationCallbackDataDTO());
        $secondPage      = $this->callbackDataFactory->resolve(new PaginationCallbackDataDTO(2));

        $anime = $animeCollection->first();
        $this->assertInstanceOf(Anime::class, $anime);

        $media = [
            'type'        => 'photo',
            'media'       => $anime->image->path,
            'caption'     => $anime->toTelegramCaption,
            'has_spoiler' => false,
        ];

        $replyMarkup = [
            'inline_keyboard' => [
                [
                    [
                        'text' => $anime->urls->first()->domain,
                        'url'  => $anime->urls->first()->url,
                    ],
                ],
                [

                    [
                        'text'          => '>',
                        'callback_data' => $secondPage,
                    ],
                ],
            ],
        ];

        $this->bot->hearCallbackQueryData($firstPage)
                  ->reply()
                  ->assertReplyMessage([
                      'media'        => json_encode($media),
                      'reply_markup' => json_encode($replyMarkup),
                  ]);
    }
}
