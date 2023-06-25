<?php

declare(strict_types=1);

namespace Tests\Feature\Telegram\Handlers;

use App\Enums\AnimeStatusEnum;
use App\Enums\Telegram\Commands\CommandEnum;
use App\Enums\Telegram\Handlers\AddAnimeHandlerEnum;
use App\Enums\Validation\SupportedUrlEnum;
use App\Facades\Telegram\State\UserStateFacade;
use App\Telegram\Handlers\AddAnimeHandler;
use Closure;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;
use Tests\Traits\CanCreateFakeData;
use Tests\Traits\CanCreateFakeUpdates;
use Tests\Traits\CanCreateMocks;
use WeStacks\TeleBot\Objects\Message;

class AddAnimeHandlerTest extends TestCase
{
    use RefreshDatabase,
        CanCreateMocks,
        CanCreateFakeUpdates,
        CanCreateFakeData;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpFakeBot();
        $this->setUpFakeCloudinary();

        $this->bot->addHandler([AddAnimeHandler::class]);

        UserStateFacade::shouldReceive('getLastExecutedCommand')
                       ->with(self::FAKE_TELEGRAM_ID)
                       ->andReturn(CommandEnum::ADD_ANIME_BUTTON->value);
    }

    /**
     * @return array<array<string>>
     */
    public static function supportedUrlsProvider(): array
    {
        return [
            ['https://animego.org/anime/blich-tysyacheletnyaya-krovavaya-voyna-2129'],
            ['https://animevost.org/tip/tv/5-naruto-shippuuden12.html'],
        ];
    }

    /**
     * @return void
     */
    public function testBotWillNotScrapeInvalidMessage(): void
    {
        $update   = $this->createFakeStickerMessageUpdate();
        $response = $this->bot->handleUpdate($update);

        $this->assertInstanceOf(Closure::class, $response);
        $this->assertNull($response());
    }

    /**
     * @return void
     */
    public function testBotWillNotScrapeUnsupportedUrl(): void
    {
        $update   = $this->createFakeTextMessageUpdate($this->faker->url);
        $response = $this->bot->handleUpdate($update);

        $this->assertInstanceOf(Message::class, $response);
        $this->assertEquals(SupportedUrlEnum::UNSUPPORTED_URL->value, $response->text);
    }

    /**
     * @dataProvider supportedUrlsProvider
     * @param string $url
     * @return void
     */
    public function testBotWillCanReturnAnimeWithoutScrapingIfUrlIsAlreadyInDatabase(string $url): void
    {
        UserStateFacade::shouldReceive('resetExecutedCommandsList')->with(self::FAKE_TELEGRAM_ID)->once();

        $animeList = $this->createRandomAnimeWithRelations();
        $animeList->first()->urls()->create(['url' => $url]);

        $update   = $this->createFakeTextMessageUpdate($url);
        $response = $this->bot->handleUpdate($update);

        $this->assertInstanceOf(Message::class, $response);
        $this->assertEquals(AddAnimeHandlerEnum::PARSE_HAS_ENDED->value, $response->text);
    }

    /**
     * @dataProvider supportedUrlsProvider
     * @param string $url
     * @return void
     */
    public function testBotCanScrapeSupportedUrls(string $url): void
    {
        Http::fake(
            [
                '*' => [
                    'url'      => $url,
                    'title'    => $this->faker->sentence,
                    'status'   => $this->faker->randomElement(AnimeStatusEnum::values()),
                    'episodes' => $this->faker->randomAscii,
                    'rating'   => $this->faker->randomFloat(),
                ],
            ]
        );

        Cloudinary::shouldReceive('uploadFile')->andReturnSelf();
        Cloudinary::shouldReceive('getSecurePath')->andReturn($this->faker->imageUrl);

        UserStateFacade::shouldReceive('resetExecutedCommandsList')->with(self::FAKE_TELEGRAM_ID)->once();

        $update   = $this->createFakeTextMessageUpdate($url);
        $response = $this->bot->handleUpdate($update);

        $this->assertInstanceOf(Message::class, $response);
        $this->assertEquals(AddAnimeHandlerEnum::PARSE_HAS_ENDED->value, $response->text);
    }
}
