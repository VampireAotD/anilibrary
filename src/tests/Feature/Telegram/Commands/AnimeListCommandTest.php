<?php

declare(strict_types=1);

namespace Tests\Feature\Telegram\Commands;

use App\Enums\Telegram\Actions\CommandEnum;
use App\ValueObject\Telegram\Anime\AnimeCaptionText;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CanCreateMocks;
use Tests\Concerns\Fake\CanCreateFakeAnime;
use Tests\Feature\Telegram\Callbacks\AnimeListCallbackTest;
use Tests\TestCase;

final class AnimeListCommandTest extends TestCase
{
    use RefreshDatabase;
    use CanCreateMocks;
    use CanCreateFakeAnime;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpFakeBot();
    }

    public function testWillNotRespondWithAnimeListIfDatabaseIsEmpty(): void
    {
        $this->bot->hearText(CommandEnum::ANIME_LIST_COMMAND->value)
                  ->reply()
                  ->assertReplyMessage([
                      'text' => __('telegram.callbacks.anime_list.render_error'),
                  ]);
    }

    /**
     * This case only checks that command renders first page of a list. To check pagination
     * @see AnimeListCallbackTest
     */
    public function testCanRespondWithAnimeList(): void
    {
        $anime = $this->createAnimeWithRelations();

        $this->bot->hearText(CommandEnum::ANIME_LIST_COMMAND->value)
                  ->reply()
                  ->assertReplyMessage([
                      'photo'   => $anime->image->path,
                      'caption' => (string) AnimeCaptionText::fromAnime($anime),
                  ]);
    }
}
