<?php

namespace App\Handlers;

use App\Handlers\History\UserHistory;
use App\Handlers\Traits\CanConvertAnimeToCaption;
use App\Repositories\Contracts\Anime\AnimeRepositoryInterface;
use WeStacks\TeleBot\Interfaces\UpdateHandler;
use WeStacks\TeleBot\Objects\InputMedia\InputMediaPhoto;
use WeStacks\TeleBot\Objects\Update;
use WeStacks\TeleBot\TeleBot;
use App\Enums\CallbackQueryEnum;

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

        $this->animeRepository = app(AnimeRepositoryInterface::class);
    }

    /**
     * @param Update $update
     * @param TeleBot $bot
     * @return bool
     */
    public static function trigger(Update $update, TeleBot $bot): bool
    {
        return isset($update->callback_query);
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
                    $anime = $this->animeRepository->findById($callbackParameters['animeId']);
                    $this->sendPhoto($this->convertToCaption($anime));
                    break;
                case CallbackQueryEnum::PAGINATION->value:
                    $page = $callbackParameters['page'] ?? 1;
                    $list = $this->animeRepository->paginate(currentPage: $page);

                    $caption = $this->convertToCaption(
                        $list->first(),
                        $this->update->callback_query->from->id,
                        $list
                    );

                    try {
                        $this->editMessageMedia([
                            'media' => new InputMediaPhoto(
                                [
                                    'media' => $caption['photo'],
                                    'type' => 'photo',
                                    'caption' => $caption['caption']
                                ]
                            ),
                            'reply_markup' => $caption['reply_markup']
                        ]);
                    } catch (\Exception $exception) {
                        // Prevent bot from breaking because of next or prev page spam
                        logger()->info('Probably spam from buttons', [
                            'exceptionMessage' => $exception->getMessage(),
                            'exceptionTrace' => $exception->getTraceAsString(),
                        ]);
                    }
                    break;
                default:
                    break;
            }
        }
    }
}
