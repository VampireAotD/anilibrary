<?php

declare(strict_types=1);

namespace App\Telegram\Callbacks;

use App\DTO\UseCase\Telegram\Caption\ViewEncodedAnimeDTO;
use App\Enums\Telegram\Callbacks\CallbackQueryTypeEnum;
use App\Enums\Telegram\Callbacks\ViewAnimeCallbackEnum;
use App\Telegram\Callbacks\Traits\CanSafelyRetrieveArguments;
use App\UseCase\Telegram\CaptionUseCase;
use Exception;
use GuzzleHttp\Promise\PromiseInterface;
use WeStacks\TeleBot\Handlers\CallbackHandler;
use WeStacks\TeleBot\Objects\Message;
use WeStacks\TeleBot\Objects\Update;
use WeStacks\TeleBot\TeleBot;

/**
 * Class ViewAnimeCallback
 * @package App\Telegram\Callbacks
 */
final class ViewAnimeCallback extends CallbackHandler
{
    use CanSafelyRetrieveArguments;

    private CaptionUseCase $callbackQueryUseCase;

    public function __construct(TeleBot $bot, Update $update)
    {
        parent::__construct($bot, $update);

        $command = CallbackQueryTypeEnum::VIEW_ANIME->value;

        $this->match                = "#command=({$command})&animeId=(\w+)#m";
        $this->callbackQueryUseCase = app(CaptionUseCase::class);
    }

    /**
     * @return PromiseInterface|Message|void
     */
    public function handle()
    {
        $arguments = $this->safelyRetrieveArguments();

        if (!$arguments) {
            return;
        }

        [, $encodedId] = $arguments;

        $chatId = $this->update->chat()->id;

        try {
            $caption = $this->callbackQueryUseCase->createDecodedAnimeCaption(
                new ViewEncodedAnimeDTO($encodedId, $chatId)
            );

            if (!$caption) {
                return $this->sendMessage(['text' => ViewAnimeCallbackEnum::FAILED_TO_GET_ANIME->value]);
            }

            return $this->sendPhoto($caption);
        } catch (Exception $exception) {
            logger()->error(
                'View anime callback',
                [
                    'exception_message' => $exception->getMessage(),
                    'exception_trace'   => $exception->getTraceAsString(),
                ]
            );
        }
    }
}
