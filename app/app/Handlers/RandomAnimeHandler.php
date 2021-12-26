<?php

namespace App\Handlers;

use App\Handlers\Traits\CanConvertAnimeToCaption;
use App\Repositories\Contracts\Anime\Repository as AnimeRepository;
use WeStacks\TeleBot\Interfaces\UpdateHandler;
use WeStacks\TeleBot\Objects\Update;
use WeStacks\TeleBot\TeleBot;
use App\Enums\KeyboardEnum;

/**
 * Class RandomAnimeHandler
 * @package App\Handlers
 */
class RandomAnimeHandler extends UpdateHandler
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
        return end(CommandHandler::$executedCommands) === KeyboardEnum::RANDOM_ANIME->value;
    }

    /**
     * @return void
     */
    public function handle(): void
    {
        try {
            $randomAnime = $this->animeRepository->findRandomAnime();

            $this->sendPhoto($this->convertToCaption($randomAnime));
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
