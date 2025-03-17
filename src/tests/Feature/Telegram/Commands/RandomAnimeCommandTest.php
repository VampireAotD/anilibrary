<?php

declare(strict_types=1);

namespace Tests\Feature\Telegram\Commands;

use App\Enums\Telegram\Actions\CommandEnum;
use App\Enums\Telegram\Buttons\CommandButtonEnum;
use App\ValueObject\Telegram\Anime\AnimeCaptionText;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\Fake\CanCreateFakeAnime;
use Tests\Concerns\Fake\CanCreateFakeTelegramBot;
use Tests\TestCase;

final class RandomAnimeCommandTest extends TestCase
{
    use RefreshDatabase;
    use CanCreateFakeTelegramBot;
    use CanCreateFakeAnime;

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpFakeBot();
    }

    public function testWillRespondWithErrorMessageIfDatabaseIsEmpty(): void
    {
        $this->bot->hearText(CommandEnum::RANDOM_ANIME_COMMAND->value)->reply()->assertReplyMessage(
            ['text' => __('telegram.commands.random_anime.unable_to_find_anime')]
        );
    }

    public function testWillRespondWithErrorMessageWhenButtonIsPressedAndDatabaseIsEmpty(): void
    {
        $this->bot->hearText(CommandButtonEnum::RANDOM_ANIME_BUTTON->value)->reply()->assertReplyMessage(
            ['text' => __('telegram.commands.random_anime.unable_to_find_anime')]
        );
    }

    public function testWillRespondWithAnimeMessage(): void
    {
        $anime = $this->createAnimeWithRelations();

        $this->bot->hearText(CommandEnum::RANDOM_ANIME_COMMAND->value)->reply()->assertReplyMessage([
            'photo'   => $anime->image->path,
            'caption' => (string) AnimeCaptionText::fromAnime($anime),
        ]);
    }
}
