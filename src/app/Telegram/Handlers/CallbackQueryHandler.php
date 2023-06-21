<?php

declare(strict_types=1);

namespace App\Telegram\Handlers;

use App\DTO\UseCase\Telegram\CallbackQuery\PaginationDTO;
use App\DTO\UseCase\Telegram\CallbackQuery\ViewAnimeDTO;
use App\Enums\Telegram\CallbackQueryTypeEnum;
use App\Facades\Telegram\History\UserHistory;
use App\UseCase\Telegram\CallbackQueryUseCase;
use Exception;
use GuzzleHttp\Promise\PromiseInterface;
use WeStacks\TeleBot\Exceptions\TeleBotException;
use WeStacks\TeleBot\Handlers\UpdateHandler;
use WeStacks\TeleBot\Objects\Message;
use WeStacks\TeleBot\Objects\Update;
use WeStacks\TeleBot\TeleBot;

/**
 * Class CallbackQueryHandler
 * @package App\Telegram\Handlers
 */
class CallbackQueryHandler extends UpdateHandler
{
    private CallbackQueryUseCase $callbackQueryUseCase;

    public function __construct(TeleBot $bot, Update $update)
    {
        parent::__construct($bot, $update);

        $this->callbackQueryUseCase = app(CallbackQueryUseCase::class);
    }

    /**
     * @return bool
     */
    public function trigger(): bool
    {
        return isset($this->update->callback_query);
    }

    /**
     * @return bool|PromiseInterface|void|Message
     */
    public function handle()
    {
        $callbackData = $this->update->callback_query->data;
        $chatId       = $this->update->chat()->id;

        UserHistory::addLastActiveTime($chatId);
        parse_str($callbackData, $callbackParameters);

        switch ($callbackParameters['command'] ?? '') {
            case CallbackQueryTypeEnum::VIEW_ANIME->value:
                $caption = $this->callbackQueryUseCase->createAnimeCaption(
                    new ViewAnimeDTO($callbackParameters['animeId'], $chatId)
                );

                if (!$caption) {
                    return $this->sendMessage(
                        [
                            'text'    => 'Не удалось найти тайтл по данному запросу',
                            'chat_id' => $chatId,
                        ]
                    );
                }

                return $this->sendPhoto(
                    $this->callbackQueryUseCase->createAnimeCaption(
                        new ViewAnimeDTO($callbackParameters['animeId'], $chatId)
                    )
                );
            case CallbackQueryTypeEnum::ANIME_LIST->value:
                $page    = (int) ($callbackParameters['page'] ?? 1);
                $caption = $this->callbackQueryUseCase->createPaginationCaption(new PaginationDTO($chatId, $page));

                try {
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
                return;
            default:
                return;
        }
    }
}
