<?php

declare(strict_types=1);

namespace Tests\Feature\Telegram\Callbacks;

use App\DTO\Factory\Telegram\CallbackData\PaginationCallbackDataDTO;
use App\Factory\Telegram\CallbackData\CallbackDataFactory;
use App\Models\Anime;
use App\ValueObject\Telegram\Anime\AnimeCaptionText;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\Fake\CanCreateFakeAnime;
use Tests\Concerns\Fake\CanCreateFakeTelegramBot;
use Tests\TestCase;

final class AnimeListCallbackTest extends TestCase
{
    use RefreshDatabase;
    use CanCreateFakeTelegramBot;
    use CanCreateFakeAnime;

    private CallbackDataFactory $callbackDataFactory;

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpFakeBot();
        $this->callbackDataFactory = $this->app->make(CallbackDataFactory::class);
    }

    public function testWillNotRespondWithAnimeListIfDatabaseIsEmpty(): void
    {
        $callbackData = $this->callbackDataFactory->resolve(new PaginationCallbackDataDTO());

        $this->bot->hearCallbackQueryData($callbackData)
                  ->reply()
                  ->assertReplyMessage(['text' => __('telegram.callbacks.anime_list.render_error')]);
    }

    public function testWillSwitchAnimeListPage(): void
    {
        /** @var Collection<int, Anime> $list */
        $list       = $this->createAnimeCollectionWithRelations(quantity: 2);
        $firstPage  = $this->callbackDataFactory->resolve(new PaginationCallbackDataDTO());
        $secondPage = $this->callbackDataFactory->resolve(new PaginationCallbackDataDTO(2));

        $firstAnime = $list->first();
        $lastAnime  = $list->last();

        // First page render
        $this->bot->hearCallbackQueryData($firstPage)
                  ->reply()
                  ->assertReplyMessage([
                      'media' => json_encode([
                          'type'        => 'photo',
                          'media'       => $firstAnime->image->path,
                          'caption'     => (string) AnimeCaptionText::fromAnime($firstAnime),
                          'has_spoiler' => false,
                      ]),
                      'reply_markup' => json_encode([
                          'inline_keyboard' => [
                              [
                                  [
                                      'text' => $firstAnime->urls->first()->domain,
                                      'url'  => $firstAnime->urls->first()->url,
                                  ],
                              ],
                              [
                                  [
                                      'text'          => '>',
                                      'callback_data' => $secondPage,
                                  ],
                              ],
                          ],
                      ]),
                  ]);

        // Second page render
        $this->bot->hearCallbackQueryData($secondPage)
                  ->reply()
                  ->assertReplyMessage([
                      'media' => json_encode([
                          'type'        => 'photo',
                          'media'       => $lastAnime->image->path,
                          'caption'     => (string) AnimeCaptionText::fromAnime($lastAnime),
                          'has_spoiler' => false,
                      ]),
                      'reply_markup' => json_encode([
                          'inline_keyboard' => [
                              [
                                  [
                                      'text' => $lastAnime->urls->first()->domain,
                                      'url'  => $lastAnime->urls->first()->url,
                                  ],
                              ],
                              [
                                  [
                                      'text'          => '<',
                                      'callback_data' => $firstPage,
                                  ],
                              ],
                          ],
                      ]),
                  ]);
    }
}
