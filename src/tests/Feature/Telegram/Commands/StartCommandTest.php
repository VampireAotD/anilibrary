<?php

declare(strict_types=1);

namespace Tests\Feature\Telegram\Commands;

use App\Enums\Telegram\StartCommandEnum;
use App\Jobs\Telegram\RegisterUserJob;
use App\Telegram\Commands\StartCommand;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;
use Tests\Traits\CanCreateFakeUpdates;
use Tests\Traits\CanCreateMocks;
use WeStacks\TeleBot\TeleBot;

class StartCommandTest extends TestCase
{
    use CanCreateMocks,
        CanCreateFakeUpdates;

    protected TeleBot $bot;

    protected function setUp(): void
    {
        parent::setUp();

        $this->bot = $this->createFakeBot();
        $this->bot->addHandler([StartCommand::class]);
    }

    public function testCommandWillShowMenuAndRegisterNewUser(): void
    {
        Bus::fake();

        $update   = $this->createFakeCommandUpdate('/start');
        $response = $this->bot->handleUpdate($update);

        Bus::assertDispatched(RegisterUserJob::class);

        $this->assertEquals(StartCommandEnum::WELCOME_MESSAGE->value, $response->text);
    }
}
