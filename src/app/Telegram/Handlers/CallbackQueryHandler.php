<?php

declare(strict_types=1);

namespace App\Telegram\Handlers;

use App\DTO\Service\Telegram\CreateAnimeCaptionDTO;
use App\Enums\Telegram\CallbackQueryEnum;
use App\Repositories\Contracts\AnimeRepositoryInterface;
use App\Services\Telegram\CaptionService;
use App\Telegram\History\UserHistory;
use GuzzleHttp\Promise\PromiseInterface;
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
    private AnimeRepositoryInterface $animeRepository;
    private CaptionService           $captionService;

    public function __construct(TeleBot $bot, Update $update)
    {
        parent::__construct($bot, $update);

        $this->animeRepository = app(AnimeRepositoryInterface::class);
        $this->captionService  = app(CaptionService::class);
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

        if (isset($callbackParameters['command'])) {
            switch ($callbackParameters['command']) {
                case CallbackQueryEnum::CHECK_ADDED_ANIME->value:
                    $animeId = $this->decode($callbackParameters['animeId']);
                    $anime   = $this->animeRepository->findById($animeId);

                    return $this->sendPhoto($this->captionService->create(new CreateAnimeCaptionDTO($anime, $chatId)));
                case CallbackQueryEnum::PAGINATION->value:
                    $page = (int) $callbackParameters['page'] ?? 1;
                    $list = $this->animeRepository->paginate(currentPage: $page);

                    $caption = $this->captionService->create(new CreateAnimeCaptionDTO($list->first(), $chatId, $list));

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
                    } catch (\Exception $exception) {
                        // Prevent bot from breaking because of next or prev page spam
                        logger()->info(
                            'Probably spam from buttons',
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
}
