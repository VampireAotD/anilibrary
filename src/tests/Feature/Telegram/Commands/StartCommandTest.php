<?php

declare(strict_types=1);

namespace Tests\Feature\Telegram\Commands;

use App\Enums\Telegram\Commands\StartCommandEnum;
use App\Jobs\Telegram\RegisterUserJob;
use App\Telegram\Commands\StartCommand;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;
use Tests\Traits\CanCreateFakeUpdates;
use Tests\Traits\CanCreateMocks;

class StartCommandTest extends TestCase
{
    use CanCreateMocks,
        CanCreateFakeUpdates;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpFakeBot();
        $this->bot->addHandler([StartCommand::class]);
    }

    public function testCommandWillNotRegisterUserIfThereIsNoInfoAboutHim(): void
    {
        Bus::fake();

        $update   = $this->createFakeCommandUpdate('/start');
        $response = $this->bot->handleUpdate($update);

        Bus::assertNotDispatched(RegisterUserJob::class);

        $this->assertEquals(StartCommandEnum::WELCOME_MESSAGE->value, $response->text);
    }

    public function testCommandWillNotRegisterUserIfHeIsBot(): void
    {
        Bus::fake();

        $update   = $this->createFakeCommandUpdateWithBot('/start');
        $response = $this->bot->handleUpdate($update);

        Bus::assertNotDispatched(RegisterUserJob::class);

        $this->assertEquals(StartCommandEnum::WELCOME_MESSAGE->value, $response->text);
    }

    public function testCommandWillShowMenuAndRegisterNewUserIfHeIsNotBot(): void
    {
        Bus::fake();

        $update   = $this->createFakeCommandUpdateWithUser('/start');
        $response = $this->bot->handleUpdate($update);

        Bus::assertDispatched(RegisterUserJob::class);

        $this->assertEquals(StartCommandEnum::WELCOME_MESSAGE->value, $response->text);
    }
}
