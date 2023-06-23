<?php

declare(strict_types=1);

namespace Tests\Feature\Telegram\Handlers;

use App\Enums\Telegram\Commands\CommandEnum;
use App\Enums\Telegram\Handlers\MessageHandlerEnum;
use App\Telegram\Handlers\MessageHandler;
use Tests\TestCase;
use Tests\Traits\CanCreateFakeUpdates;
use Tests\Traits\CanCreateMocks;
use WeStacks\TeleBot\Objects\Message;

class MessageHandlerTest extends TestCase
{
    use CanCreateMocks,
        CanCreateFakeUpdates;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpFakeBot();
        $this->bot->addHandler([MessageHandler::class]);
    }

    /**
     * @return array<array<string>>
     */
    public static function addNewTitleCommandProvider(): array
    {
        return [
            [CommandEnum::ADD_NEW_TITLE_COMMAND->value],
            [CommandEnum::ADD_ANIME_BUTTON->value],
        ];
    }

    /**
     * @dataProvider addNewTitleCommandProvider
     * @param string $command
     * @return void
     */
    public function testBotWillInformHowToAddAnime(string $command): void
    {
        $update   = $this->createFakeTextMessageUpdate(message: $command);
        $response = $this->bot->handleUpdate($update);

        $this->assertInstanceOf(Message::class, $response);
        $this->assertEquals(MessageHandlerEnum::PROVIDE_URL->value, $response->text);
    }
}
