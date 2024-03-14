<?php

declare(strict_types=1);

namespace Feature\Telegram\Commands;

use App\Enums\Telegram\Actions\CommandEnum;
use App\Enums\Telegram\Buttons\CommandButtonEnum;
use App\Facades\Telegram\State\UserStateFacade;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Concerns\CanCreateMocks;
use Tests\Concerns\Fake\CanCreateFakeAnime;

class RandomAnimeCommandTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    use CanCreateMocks;
    use CanCreateFakeAnime;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpFakeBot();
    }

    public function testHandlerWillRespondWithTextMessageIfDatabaseIsEmpty(): void
    {
        UserStateFacade::shouldReceive('resetExecutedCommandsList')->once();

        $this->bot->hearText(CommandEnum::RANDOM_ANIME_COMMAND->value)->reply()->assertReplyMessage(
            ['text' => __('telegram.commands.random_anime.unable_to_find_anime')]
        );
    }

    public function testCommandWillRespondWhenButtonIsPressed(): void
    {
        UserStateFacade::shouldReceive('resetExecutedCommandsList')->once();

        $this->bot->hearText(CommandButtonEnum::RANDOM_ANIME_BUTTON->value)->reply()->assertReplyMessage(
            ['text' => __('telegram.commands.random_anime.unable_to_find_anime')]
        );
    }

    public function testCommandWillSendMessageWithAnimeData(): void
    {
        UserStateFacade::shouldReceive('resetExecutedCommandsList')->once();

        $anime = $this->createAnimeWithRelations();

        $this->bot->hearText(CommandEnum::RANDOM_ANIME_COMMAND->value)->reply()->assertReplyMessage([
            'photo'   => $anime->image->path,
            'caption' => $anime->toTelegramCaption,
        ]);
    }
}
