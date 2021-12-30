<?php

namespace App\Handlers;

use App\Handlers\History\UserHistory;
use App\Handlers\Traits\CanConvertAnimeToCaption;
use App\Repositories\Contracts\Anime\Repository as AnimeRepository;
use WeStacks\TeleBot\Interfaces\UpdateHandler;
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

    private AnimeRepository $animeRepository;

    public function __construct(TeleBot $bot, Update $update)
    {
        parent::__construct($bot, $update);

        $this->animeRepository = app(AnimeRepository::class);
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

        $callbackParameters = explode(',', $callbackData);

        switch (reset($callbackParameters)) {
            case CallbackQueryEnum::CHECK_ADDED_ANIME->value:
                $anime = $this->animeRepository->findById(end($callbackParameters));
                $this->sendPhoto($this->convertToCaption($anime));
                break;
            default:
        }
    }
}
