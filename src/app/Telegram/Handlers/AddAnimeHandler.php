<?php

declare(strict_types=1);

namespace App\Telegram\Handlers;

use App\DTO\Factory\Telegram\CallbackData\ViewAnimeCallbackDataDTO;
use App\Enums\Telegram\Commands\CommandEnum;
use App\Enums\Telegram\Handlers\AddAnimeHandlerEnum;
use App\Enums\Validation\Telegram\SupportedUrlRuleEnum;
use App\Facades\Telegram\State\UserStateFacade;
use App\Factory\Telegram\CallbackData\CallbackDataFactory;
use App\Models\Anime;
use App\Rules\Telegram\SupportedUrlRule;
use App\Services\AnimeService;
use App\UseCase\Scraper\AnimeUseCase;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Throwable;
use WeStacks\TeleBot\Objects\Message;
use WeStacks\TeleBot\Objects\Update;
use WeStacks\TeleBot\TeleBot;

/**
 * Class AddAnimeHandler
 * @package App\Telegram\Handlers
 */
final class AddAnimeHandler extends TextMessageUpdateHandler
{
    protected array $allowedMessages = [CommandEnum::ADD_ANIME_COMMAND->value, CommandEnum::ADD_ANIME_BUTTON->value];

    private AnimeUseCase        $animeUseCase;
    private AnimeService        $animeService;
    private CallbackDataFactory $callbackDataFactory;

    public function __construct(TeleBot $bot, Update $update)
    {
        parent::__construct($bot, $update);

        $this->animeUseCase        = app(AnimeUseCase::class);
        $this->animeService        = app(AnimeService::class);
        $this->callbackDataFactory = app(CallbackDataFactory::class);
    }

    /**
     * @return PromiseInterface|Message|void
     */
    public function handle()
    {
        $message = $this->update->message;
        $chatId  = $message->chat->id;

        if (in_array($message->text, $this->allowedMessages, true)) {
            return;
        }

        $validUrl = Validator::make(['url' => $message->text], ['url' => ['required', new SupportedUrlRule()]])->passes(
        );

        if (!$validUrl) {
            return $this->sendMessage(['text' => SupportedUrlRuleEnum::UNSUPPORTED_URL->value]);
        }

        try {
            if ($anime = $this->animeService->findByUrl($message->text)) {
                UserStateFacade::resetExecutedCommandsList($chatId);

                return $this->sendScrapedMessage($anime);
            }

            $anime   = $this->animeUseCase->scrapeAndCreateAnime($message->text, $chatId);
            $message = $this->sendScrapedMessage($anime);

            UserStateFacade::resetExecutedCommandsList($chatId);

            return $message;
        } catch (RequestException | ValidationException | Throwable $exception) {
            logger()->error(
                'Add anime handler',
                [
                    'url'              => $message->text,
                    'exceptionMessage' => $exception->getMessage(),
                    'exceptionTrace'   => $exception->getTraceAsString(),
                ]
            );

            return $this->sendMessage(['text' => AddAnimeHandlerEnum::PARSE_FAILED->value]);
        }
    }

    private function sendScrapedMessage(Anime $anime): Message | PromiseInterface
    {
        $callbackData = $this->callbackDataFactory->resolve(new ViewAnimeCallbackDataDTO($anime->id));

        return $this->sendMessage(
            [
                'text'         => AddAnimeHandlerEnum::PARSE_HAS_ENDED->value,
                'reply_markup' => [
                    'inline_keyboard' => [
                        [
                            [
                                'text'          => AddAnimeHandlerEnum::VIEW_ANIME->value,
                                'callback_data' => $callbackData,
                            ],
                        ],
                    ],
                ],
            ]
        );
    }
}
