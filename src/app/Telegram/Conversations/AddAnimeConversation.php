<?php

declare(strict_types=1);

namespace App\Telegram\Conversations;

use App\DTO\Factory\Telegram\CallbackData\ViewAnimeCallbackDataDTO;
use App\Factory\Telegram\CallbackData\CallbackDataFactory;
use App\Models\Anime;
use App\Rules\Telegram\SupportedUrlRule;
use App\Services\Anime\AnimeService;
use App\UseCase\Scraper\ScraperUseCase;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use SergiX44\Nutgram\Conversations\Conversation;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;
use Throwable;

final class AddAnimeConversation extends Conversation
{
    public function __construct(
        private readonly AnimeService        $animeService,
        private readonly ScraperUseCase      $scraperUseCase,
        private readonly CallbackDataFactory $callbackDataFactory
    ) {
    }

    public function start(Nutgram $bot): void
    {
        $bot->sendMessage(__('telegram.conversations.add_anime.provide_url'));

        $this->next('scrape');
    }

    public function scrape(Nutgram $bot): void
    {
        $url    = $bot->message()?->text;
        $errors = Validator::make(
            data : ['url' => $url],
            rules: ['url' => ['required', new SupportedUrlRule()]],
        )->errors();

        if ($errors->isNotEmpty()) {
            $bot->sendMessage($errors->first());
            return;
        }

        try {
            if ($anime = $this->animeService->findByUrl($url)) {
                $this->sendScrapedMessage($anime);
                $this->end();
                return;
            }

            $anime = $this->scraperUseCase->scrapeByUrl($url);

            $this->sendScrapedMessage($anime);
            $this->end();
        } catch (RequestException | ValidationException | Throwable $exception) {
            Log::error('Add anime conversation', [
                'exceptionMessage' => $exception->getMessage(),
                'exceptionTrace'   => $exception->getTraceAsString(),
                'url'              => $url,
            ]);

            $bot->sendMessage(__('telegram.conversations.add_anime.scrape_failed'));
            $this->end();
        }
    }

    private function sendScrapedMessage(Anime $anime): void
    {
        $callbackData = $this->callbackDataFactory->resolve(new ViewAnimeCallbackDataDTO($anime->id));

        $this->bot->sendMessage(
            text        : __('telegram.conversations.add_anime.scrape_has_ended'),
            reply_markup: InlineKeyboardMarkup::make()->addRow(
                InlineKeyboardButton::make(
                    text         : __('telegram.conversations.add_anime.view_anime'),
                    callback_data: $callbackData
                )
            )
        );
    }
}
