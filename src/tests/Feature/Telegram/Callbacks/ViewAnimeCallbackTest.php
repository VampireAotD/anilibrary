<?php

declare(strict_types=1);

namespace Tests\Feature\Telegram\Callbacks;

use App\DTO\Factory\Telegram\CallbackData\ViewAnimeCallbackDataDTO;
use App\Enums\Telegram\Callbacks\ViewAnimeCallbackEnum;
use App\Factory\Telegram\CallbackData\CallbackDataFactory;
use App\Telegram\Callbacks\ViewAnimeCallback;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;
use Tests\Traits\CanCreateFakeUpdates;
use Tests\Traits\CanCreateMocks;
use WeStacks\TeleBot\Objects\Message;

class ViewAnimeCallbackTest extends TestCase
{
    use RefreshDatabase,
        CanCreateMocks,
        CanCreateFakeUpdates;

    private CallbackDataFactory $callbackDataFactory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpFakeBot();
        $this->bot->addHandler([ViewAnimeCallback::class]);

        $this->callbackDataFactory = $this->app->make(CallbackDataFactory::class);
    }

    public function testCallbackWillRespondWithFailureMessageItCouldNotMakeCaptionForEncodedId(): void
    {
        $callbackData = $this->callbackDataFactory->resolve(
            new ViewAnimeCallbackDataDTO(Str::uuid()->toString())
        );

        $update   = $this->createFakeCallbackQueryUpdate($callbackData);
        $response = $this->bot->handleUpdate($update);

        $this->assertInstanceOf(Message::class, $response);
        $this->assertEquals(ViewAnimeCallbackEnum::FAILED_TO_GET_ANIME->value, $response->text);
    }
}
