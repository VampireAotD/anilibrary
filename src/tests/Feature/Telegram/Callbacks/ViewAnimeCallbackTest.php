<?php

declare(strict_types=1);

namespace Tests\Feature\Telegram\Callbacks;

use App\DTO\Factory\Telegram\CallbackData\ViewAnimeCallbackDataDTO;
use App\Factory\Telegram\CallbackData\CallbackDataFactory;
use App\ValueObject\Telegram\Anime\AnimeCaptionText;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\Concerns\CanCreateMocks;
use Tests\Concerns\Fake\CanCreateFakeAnime;
use Tests\TestCase;

final class ViewAnimeCallbackTest extends TestCase
{
    use RefreshDatabase;
    use CanCreateMocks;
    use CanCreateFakeAnime;

    private CallbackDataFactory $callbackDataFactory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpFakeBot();
        $this->callbackDataFactory = $this->app->make(CallbackDataFactory::class);
    }

    public function testWillRespondWithErrorMessageIfAnimeDoesNotExist(): void
    {
        // Create callback data for non-existing anime
        $callbackData = $this->callbackDataFactory->resolve(new ViewAnimeCallbackDataDTO(Str::uuid()->toString()));

        $this->bot->hearCallbackQueryData($callbackData)->reply()->assertReplyMessage([
            'text' => __('telegram.callbacks.view_anime.render_error'),
        ]);
    }

    public function testWillRespondWithAnimeMessage(): void
    {
        $anime        = $this->createAnimeWithRelations();
        $callbackData = $this->callbackDataFactory->resolve(new ViewAnimeCallbackDataDTO($anime->id));

        $this->bot->hearCallbackQueryData($callbackData)->reply()->assertReplyMessage([
            'photo'   => $anime->image->path,
            'caption' => (string) AnimeCaptionText::fromAnime($anime),
        ]);
    }
}
