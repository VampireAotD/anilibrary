<?php

declare(strict_types=1);

namespace Tests\Feature\Telegram\Middlewares;

use App\Enums\Telegram\Middlewares\BotAccessEnum;
use App\Telegram\Middlewares\BotAccessMiddleware;
use Closure;
use Tests\TestCase;
use Tests\Traits\CanCreateFakeUpdates;
use Tests\Traits\CanCreateMocks;

class BotAccessMiddlewareTest extends TestCase
{
    use CanCreateMocks,
        CanCreateFakeUpdates;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpFakeBot();
        $this->bot->addHandler([(new BotAccessMiddleware())(...)]);
    }

    /**
     * @return void
     */
    public function testUserCannotInteractWithBotIfHeIsNotAdmin(): void
    {
        $update   = $this->createFakeTextMessageUpdate();
        $response = $this->bot->handleUpdate($update);

        $this->assertEquals(BotAccessEnum::ACCESS_DENIED_MESSAGE->value, $response->text);
    }

    /**
     * @return void
     */
    public function testAdminCanInteractWithBot(): void
    {
        $update   = $this->createFakeTextMessageUpdate(config('admin.id'));
        $response = $this->bot->handleUpdate($update);

        // If user is an admin he can interact with bot commands
        // There is no other handler except middleware so response will return closure
        $this->assertInstanceOf(Closure::class, $response);
        $this->assertNull($response());
    }
}
