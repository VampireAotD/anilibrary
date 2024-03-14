<?php

declare(strict_types=1);

namespace Tests\Feature\UseCase\Telegram;

use App\DTO\UseCase\Telegram\Anime\GenerateAnimeListDTO;
use App\DTO\UseCase\Telegram\Anime\GenerateAnimeMessageDTO;
use App\DTO\UseCase\Telegram\Anime\GenerateAnimeSearchResultDTO;
use App\Exceptions\UseCase\Telegram\AnimeMessageException;
use App\Facades\Telegram\State\UserStateFacade;
use App\Models\Anime;
use App\Services\Telegram\EncoderService;
use App\UseCase\Telegram\AnimeMessageUseCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use Tests\TestCase;
use Tests\Concerns\Fake\CanCreateFakeAnime;

class AnimeMessageUseCaseTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    use CanCreateFakeAnime;

    private EncoderService      $encoderService;
    private AnimeMessageUseCase $animeMessageUseCase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->encoderService      = $this->app->make(EncoderService::class);
        $this->animeMessageUseCase = $this->app->make(AnimeMessageUseCase::class);
    }

    public function testCannotGenerateMessageForUnknownAnime(): void
    {
        $encoded = $this->encoderService->encodeId(Str::orderedUuid()->toString());

        $this->expectException(AnimeMessageException::class);
        $this->expectExceptionMessage(AnimeMessageException::animeNotFound($encoded)->getMessage());

        $this->animeMessageUseCase->generateAnimeMessage(new GenerateAnimeMessageDTO($encoded));
    }

    public function testCanGenerateAnimeMessage(): void
    {
        $anime       = $this->createAnimeWithRelations();
        $message     = $this->animeMessageUseCase->generateAnimeMessage(new GenerateAnimeMessageDTO($anime->id));
        $replyMarkup = $message->generateReplyMarkup();

        $this->assertEquals($anime->image->path, $message->photo);
        $this->assertEquals($anime->toTelegramCaption, $message->caption);
        $this->assertNotEmpty($replyMarkup->inline_keyboard);

        $row = $replyMarkup->inline_keyboard[0]; // Message has only one row, but can have multiple buttons
        $this->assertCount($anime->urls->count(), $row);

        /** @var InlineKeyboardButton $button */
        $button = reset($row);
        $this->assertNotEmpty($button);
        $this->assertEquals($anime->urls->first()->domain, $button->text);
    }

    public function testCanGenerateAnimeListMessage(): void
    {
        $animeList = $this->createAnimeCollectionWithRelations(3);

        // Starting from page 1
        // Message must have only next page button and anime information
        $message     = $this->animeMessageUseCase->generateAnimeList(new GenerateAnimeListDTO());
        $replyMarkup = $message->generateReplyMarkup();

        $first = $animeList->first();
        $this->assertInstanceOf(Anime::class, $first);
        $this->assertEquals($first->image->path, $message->photo);
        $this->assertEquals($first->toTelegramCaption, $message->caption);

        $keyboard = $replyMarkup->inline_keyboard;
        $this->assertNotEmpty($keyboard);
        $this->assertCount(2, $keyboard); // Keyboard must have 2 rows: one for urls, others for controls

        $nextPage = $keyboard[1][0]; // Next page button
        $this->assertInstanceOf(InlineKeyboardButton::class, $nextPage);
        $this->assertEquals('>', $nextPage->text);

        // Imitate next page button press
        // Message must have prev page button, next page button and anime information
        $message     = $this->animeMessageUseCase->generateAnimeList(new GenerateAnimeListDTO(page: 2));
        $replyMarkup = $message->generateReplyMarkup();

        $middle = $animeList->offsetGet(1);
        $this->assertInstanceOf(Anime::class, $middle);
        $this->assertEquals($middle->image->path, $message->photo);
        $this->assertEquals($middle->toTelegramCaption, $message->caption);

        $keyboard = $replyMarkup->inline_keyboard;
        $this->assertNotEmpty($keyboard);
        $this->assertCount(3, $keyboard); // Keyboard must have 3 rows: one for urls, others for controls

        $prevPage = $keyboard[1][0]; // Prev page button from first row
        $this->assertInstanceOf(InlineKeyboardButton::class, $prevPage);
        $this->assertEquals('<', $prevPage->text);

        $nextPage = $keyboard[2][0]; // Next page button from second row
        $this->assertInstanceOf(InlineKeyboardButton::class, $nextPage);
        $this->assertEquals('>', $nextPage->text);

        // Imitate next page button press
        // Message must have only prev page button and anime information
        $message     = $this->animeMessageUseCase->generateAnimeList(new GenerateAnimeListDTO(page: 3));
        $replyMarkup = $message->generateReplyMarkup();

        $last = $animeList->last();
        $this->assertInstanceOf(Anime::class, $last);
        $this->assertEquals($last->image->path, $message->photo);
        $this->assertEquals($last->toTelegramCaption, $message->caption);

        $keyboard = $replyMarkup->inline_keyboard;
        $this->assertNotEmpty($keyboard);
        $this->assertCount(2, $keyboard); // Keyboard must have 2 rows: one for urls, other for controls

        $prevPage = $keyboard[1][0]; // Prev page button from first row
        $this->assertInstanceOf(InlineKeyboardButton::class, $prevPage);
        $this->assertEquals('<', $prevPage->text);
    }

    public function testWillNotCreateSearchCaptionIfThereIsNoSearchResults(): void
    {
        UserStateFacade::shouldReceive('getSearchResult')->once()->andReturn([]);

        $this->expectException(AnimeMessageException::class);
        $this->expectExceptionMessage(AnimeMessageException::noSearchResultsAvailable()->getMessage());

        $this->animeMessageUseCase->generateSearchResult(
            new GenerateAnimeSearchResultDTO($this->faker->randomNumber())
        );
    }

    public function testWillCreateSearchCaptionIfThereIsSearchResults(): void
    {
        $animeList = $this->createAnimeCollectionWithRelations(2);

        UserStateFacade::shouldReceive('getSearchResult')->once()->andReturn(
            $animeList->pluck('id')->toArray()
        );

        $message = $this->animeMessageUseCase->generateSearchResult(
            new GenerateAnimeSearchResultDTO($this->faker->randomNumber())
        );

        $anime = $animeList->first();

        $this->assertInstanceOf(Anime::class, $anime);
        $this->assertEquals($anime->image->path, $message->photo);
        $this->assertEquals($anime->toTelegramCaption, $message->caption);
    }
}
