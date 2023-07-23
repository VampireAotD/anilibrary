<?php

declare(strict_types=1);

namespace App\Telegram\Callbacks;

use App\DTO\UseCase\Telegram\Caption\PaginationDTO;
use App\Enums\Telegram\Callbacks\CallbackQueryTypeEnum;
use App\Telegram\Callbacks\Traits\CanSafelyRetrieveArguments;
use App\UseCase\Telegram\CaptionUseCase;
use Exception;
use GuzzleHttp\Promise\PromiseInterface;
use WeStacks\TeleBot\Exceptions\TeleBotException;
use WeStacks\TeleBot\Handlers\CallbackHandler;
use WeStacks\TeleBot\Objects\Message;
use WeStacks\TeleBot\Objects\Update;
use WeStacks\TeleBot\TeleBot;

/**
 * Class AnimeSearchCallback
 * @package App\Telegram\Callbacks
 */
final class AnimeSearchCallback extends CallbackHandler
{
    use CanSafelyRetrieveArguments;

    protected CaptionUseCase $callbackQueryUseCase;

    public function __construct(TeleBot $bot, Update $update)
    {
        parent::__construct($bot, $update);

        $command = CallbackQueryTypeEnum::SEARCH_LIST->value;

        $this->match                = "#command=({$command})&page=(\d+)#m";
        $this->callbackQueryUseCase = app(CaptionUseCase::class);
    }

    /**
     * @return PromiseInterface|Message|bool|void
     */
    public function handle()
    {
        $arguments = $this->safelyRetrieveArguments();

        if (!$arguments) {
            return;
        }

        [, $page] = $arguments;

        $chatId = $this->update->chat()->id;

        try {
            $page    = (int) ($page ?? 1);
            $caption = $this->callbackQueryUseCase->createSearchPaginationCaption(
                new PaginationDTO($chatId, $page, CallbackQueryTypeEnum::SEARCH_LIST)
            );

            if (!$caption) {
                return;
            }

            return $this->editMessageMedia([
                'media'        => [
                    'media'   => $caption['photo'],
                    'type'    => 'photo',
                    'caption' => $caption['caption'],
                ],
                'reply_markup' => $caption['reply_markup'],
            ]);
        } catch (TeleBotException) {
            // Prevent bot from breaking because of next or prev page spam
        } catch (Exception $exception) {
            logger()->error('Anime search callback', [
                'exception_message' => $exception->getMessage(),
                'exception_trace'   => $exception->getTraceAsString(),
            ]);
        }
    }
}
