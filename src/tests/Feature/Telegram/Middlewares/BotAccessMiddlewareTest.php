<?php
declare(strict_types=1);

namespace Tests\Feature\Telegram\Middlewares;

use App\Enums\Telegram\BotAccessEnum;
use App\Telegram\Middlewares\BotAccessMiddleware;
use Closure;
use Tests\TestCase;
use Tests\Traits\CanCreateFakeUpdates;
use Tests\Traits\CanCreateMocks;
use WeStacks\TeleBot\TeleBot;

class BotAccessMiddlewareTest extends TestCase
{
    use CanCreateMocks,
        CanCreateFakeUpdates;

    private TeleBot $bot;

    protected function setUp(): void
    {
        parent::setUp();

        $this->bot = $this->createFakeBot();
        $this->bot->addHandler([new BotAccessMiddleware]);
    }

    /**
     * @return void
     */
    public function testUserCannotInteractWithBotIfHeIsNotAdmin(): void
    {
        $update   = $this->createFakeMessageUpdate();
        $response = $this->bot->handleUpdate($update);

        $this->assertEquals(BotAccessEnum::ACCESS_DENIED_MESSAGE->value, $response->text);
    }

    /**
     * @return void
     */
    public function testAdminCanInteractWithBot(): void
    {
        $update   = $this->createFakeMessageUpdate(config('admin.id'));
        $response = $this->bot->handleUpdate($update);

        // If user is an admin he can interact with bot commands
        // There is no other handler except middleware so response will return closure
        $this->assertInstanceOf(Closure::class, $response);
        $this->assertNull($response());
    }
}
