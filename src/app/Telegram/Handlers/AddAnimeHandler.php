<?php

declare(strict_types=1);

namespace App\Telegram\Handlers;

use App\DTO\Factory\Telegram\CallbackData\ViewAnimeCallbackDataDTO;
use App\Enums\Telegram\Commands\CommandEnum;
use App\Enums\Telegram\Handlers\AddAnimeHandlerEnum;
use App\Enums\Validation\SupportedUrlEnum;
use App\Exceptions\UseCase\Anime\InvalidScrapedDataException;
use App\Facades\Telegram\State\UserStateFacade;
use App\Factory\Telegram\CallbackData\CallbackDataFactory;
use App\Models\Anime;
use App\UseCase\AnimeUseCase;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Validator;
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
    private CallbackDataFactory $callbackDataFactory;

    public function __construct(TeleBot $bot, Update $update)
    {
        parent::__construct($bot, $update);

        $this->animeUseCase        = app(AnimeUseCase::class);
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

        $invalidUrl = Validator::make(['url' => $message->text], ['url' => 'required|supported_url'])->fails();

        if ($invalidUrl) {
            return $this->sendMessage(['text' => SupportedUrlEnum::UNSUPPORTED_URL->value]);
        }

        try {
            if ($anime = $this->animeUseCase->findByUrl($message->text)) {
                UserStateFacade::resetExecutedCommandsList($chatId);

                return $this->sendScrapedMessage($anime);
            }

            $anime = $this->animeUseCase->scrapeAndCreateAnime($message->text, $chatId);

            UserStateFacade::resetExecutedCommandsList($chatId);

            return $this->sendScrapedMessage($anime);
        } catch (RequestException | InvalidScrapedDataException | Throwable $exception) {
            $this->sendMessage(['text' => AddAnimeHandlerEnum::PARSE_FAILED->value]);

            logger()->error(
                'Add anime handler',
                [
                    'url'              => $message->text,
                    'exceptionMessage' => $exception->getMessage(),
                    'exceptionTrace'   => $exception->getTraceAsString(),
                ]
            );
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
