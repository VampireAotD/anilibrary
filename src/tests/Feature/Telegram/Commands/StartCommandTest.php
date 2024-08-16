<?php

declare(strict_types=1);

namespace Tests\Feature\Telegram\Commands;

use App\Enums\Telegram\Actions\CommandEnum;
use App\Jobs\Telegram\RegisterTelegramUserJob;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Tests\Concerns\CanCreateFakeUpdates;
use Tests\Concerns\CanCreateMocks;
use Tests\TestCase;

class StartCommandTest extends TestCase
{
    use RefreshDatabase;
    use CanCreateMocks;
    use CanCreateFakeUpdates;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpFakeBot();
    }

    public function testCommandWillNotRegisterUserIfThereIsNoInfoAboutHim(): void
    {
        Bus::fake();

        $response = $this->bot->hearMessage([
            'text' => CommandEnum::START_COMMAND->value,
            'from' => null,
        ])->reply();

        Bus::assertNotDispatched(RegisterTelegramUserJob::class);
        $response->assertReplyMessage(['text' => __('telegram.commands.start.welcome_message')]);
    }

    public function testCommandWillNotRegisterUserIfHeIsBot(): void
    {
        Bus::fake();

        $response = $this->bot->hearMessage([
            'text' => CommandEnum::START_COMMAND->value,
            'from' => [
                'is_bot' => true,
            ],
        ])->reply();

        Bus::assertNotDispatched(RegisterTelegramUserJob::class);
        $response->assertReplyMessage(['text' => __('telegram.commands.start.welcome_message')]);
    }

    public function testCommandWillShowMenuAndRegisterNewUserIfHeIsNotBot(): void
    {
        Bus::fake();

        $response = $this->bot->hearMessage([
            'text' => CommandEnum::START_COMMAND->value,
            'from' => [
                'is_bot' => false,
                'id'     => self::FAKE_TELEGRAM_ID,
            ],
        ])->reply();

        Bus::assertDispatched(
            RegisterTelegramUserJob::class,
            fn(RegisterTelegramUserJob $job) => $job->dto->telegramId === $response->userId()
        );

        $response->assertReplyMessage(['text' => __('telegram.commands.start.welcome_message')]);
    }
}
