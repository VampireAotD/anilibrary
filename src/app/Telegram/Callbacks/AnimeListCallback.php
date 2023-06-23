<?php

declare(strict_types=1);

namespace App\Telegram\Callbacks;

use App\DTO\UseCase\Telegram\Caption\PaginationDTO;
use App\Enums\Telegram\Callbacks\CallbackQueryTypeEnum;
use App\Telegram\Callbacks\Traits\CanSafelyReceiveCallbackArgumentsTrait;
use App\UseCase\Telegram\CaptionUseCase;
use Exception;
use GuzzleHttp\Promise\PromiseInterface;
use WeStacks\TeleBot\Exceptions\TeleBotException;
use WeStacks\TeleBot\Handlers\CallbackHandler;
use WeStacks\TeleBot\Objects\Message;
use WeStacks\TeleBot\Objects\Update;
use WeStacks\TeleBot\TeleBot;

/**
 * Class AnimeListCallback
 * @package App\Telegram\Callbacks
 */
final class AnimeListCallback extends CallbackHandler
{
    use CanSafelyReceiveCallbackArgumentsTrait;

    protected CaptionUseCase $callbackQueryUseCase;

    public function __construct(TeleBot $bot, Update $update)
    {
        parent::__construct($bot, $update);

        $command = CallbackQueryTypeEnum::ANIME_LIST->value;

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
        $page   = (int) ($page ?? 1);

        try {
            $caption = $this->callbackQueryUseCase->createPaginationCaption(new PaginationDTO($chatId, $page));

            return $this->editMessageMedia(
                [
                    'media'        => [
                        'media'   => $caption['photo'],
                        'type'    => 'photo',
                        'caption' => $caption['caption'],
                    ],
                    'reply_markup' => $caption['reply_markup'],
                ]
            );
        } catch (TeleBotException) {
            // Prevent bot from breaking because of next or prev page spam
        } catch (Exception $exception) {
            logger()->info(
                'Edit message media',
                [
                    'exceptionMessage' => $exception->getMessage(),
                    'exceptionTrace'   => $exception->getTraceAsString(),
                ]
            );
        }
    }
}
