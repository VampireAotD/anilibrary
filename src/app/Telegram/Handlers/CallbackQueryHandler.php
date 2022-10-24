<?php

declare(strict_types=1);

namespace App\Telegram\Handlers;

use App\Enums\Telegram\CallbackQueryEnum;
use App\Repositories\Contracts\Anime\AnimeRepositoryInterface;
use App\Telegram\Handlers\Traits\CanConvertAnimeToCaption;
use App\Telegram\History\UserHistory;
use WeStacks\TeleBot\Handlers\UpdateHandler;
use WeStacks\TeleBot\Objects\Update;
use WeStacks\TeleBot\TeleBot;

/**
 * Class CallbackQueryHandler
 * @package App\Handlers
 */
class CallbackQueryHandler extends UpdateHandler
{
    use CanConvertAnimeToCaption;

    private AnimeRepositoryInterface $animeRepository;

    public function __construct(TeleBot $bot, Update $update)
    {
        parent::__construct($bot, $update);

        $this->resolveBindings();
        $this->animeRepository = app(AnimeRepositoryInterface::class);
    }

    /**
     * @return bool
     */
    public function trigger(): bool
    {
        return isset($this->update->callback_query);
    }

    /**
     * @return void
     */
    public function handle(): void
    {
        $callbackData = $this->update->callback_query->data;
        UserHistory::addLastActiveTime($this->update->callback_query->from->id);

        parse_str($callbackData, $callbackParameters);

        if (isset($callbackParameters['command'])) {
            switch ($callbackParameters['command']) {
                case CallbackQueryEnum::CHECK_ADDED_ANIME->value:
                    $animeId = $this->decode($callbackParameters['animeId']);
                    $anime   = $this->animeRepository->findById($animeId);
                    $this->sendPhoto($this->convertToCaption($anime));
                    break;
                case CallbackQueryEnum::PAGINATION->value:
                    $page = (int) $callbackParameters['page'] ?? 1;
                    $list = $this->animeRepository->paginate(currentPage: $page);

                    $caption = $this->convertToCaption(
                        $list->first(),
                        $this->update->callback_query->from->id,
                        $list
                    );


                    try {
                        $this->editMessageMedia(
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
                    break;
                default:
                    break;
            }
        }
    }
}
