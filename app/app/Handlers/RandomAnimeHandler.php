<?php

namespace App\Handlers;

use App\Handlers\History\UserHistory;
use App\Handlers\Traits\CanConvertAnimeToCaption;
use App\Repositories\Contracts\Anime\Repository as AnimeRepository;
use WeStacks\TeleBot\Interfaces\UpdateHandler;
use WeStacks\TeleBot\Objects\Update;
use WeStacks\TeleBot\TeleBot;
use App\Enums\CommandEnum;

/**
 * Class RandomAnimeHandler
 * @package App\Handlers
 */
class RandomAnimeHandler extends UpdateHandler
{
    use CanConvertAnimeToCaption;

    private AnimeRepository $animeRepository;

    private const EMPTY_ANIME_DATABASE = "К сожалению сейчас бот не содержит информацию ни об одном аниме \xF0\x9F\x98\xAD";

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
        return isset($update->message) && UserHistory::userLastExecutedCommand($update->message->from->id)
            === CommandEnum::RANDOM_ANIME->value;
    }

    /**
     * @return void
     */
    public function handle(): void
    {
        try {
            $message = $this->update->message;
            UserHistory::addLastActiveTime($message->from->id);

            $randomAnime = $this->animeRepository->findRandomAnime();

            if (!$randomAnime) {
                $this->sendMessage([
                   'text' => self::EMPTY_ANIME_DATABASE,
                ]);
                UserHistory::clearUserHistory($message->from->id);
                return;
            }

            $this->sendPhoto($this->convertToCaption($randomAnime));

            UserHistory::clearUserHistory($message->from->id);
        } catch (\Exception $exception) {
            logger()->channel('single')->warning(
                $exception->getMessage(),
                [
                    'exceptionTrace' => $exception->getTraceAsString(),
                ]
            );
        }
    }
}
