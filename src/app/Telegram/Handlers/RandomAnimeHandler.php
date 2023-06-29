<?php

declare(strict_types=1);

namespace App\Telegram\Handlers;

use App\DTO\Service\Telegram\Caption\ViewAnimeCaptionDTO;
use App\Enums\Telegram\Commands\CommandEnum;
use App\Enums\Telegram\Handlers\RandomAnimeEnum;
use App\Facades\Telegram\State\UserStateFacade;
use App\Repositories\Contracts\AnimeRepositoryInterface;
use App\Services\Telegram\CaptionService;
use Exception;
use GuzzleHttp\Promise\PromiseInterface;
use WeStacks\TeleBot\Objects\Message;
use WeStacks\TeleBot\Objects\Update;
use WeStacks\TeleBot\TeleBot;

/**
 * Class RandomAnimeHandler
 * @package App\Telegram\Handlers
 */
final class RandomAnimeHandler extends TextMessageUpdateHandler
{
    protected array $allowedMessages = [
        CommandEnum::RANDOM_ANIME_COMMAND->value,
        CommandEnum::RANDOM_ANIME_BUTTON->value,
    ];

    private CaptionService           $captionService;
    private AnimeRepositoryInterface $animeRepository;

    public function __construct(TeleBot $bot, Update $update)
    {
        parent::__construct($bot, $update);

        $this->captionService  = app(CaptionService::class);
        $this->animeRepository = app(AnimeRepositoryInterface::class);
    }

    /**
     * @return PromiseInterface|Message|void
     */
    public function handle()
    {
        $chatId = $this->update->chat()->id;

        try {
            $randomAnime = $this->animeRepository->findRandomAnime();

            if (!$randomAnime) {
                return $this->sendMessage(
                    [
                        'text' => RandomAnimeEnum::UNABLE_TO_FIND_ANIME->value,
                    ]
                );
            }

            return $this->sendPhoto(
                $this->captionService->create(new ViewAnimeCaptionDTO($randomAnime, $chatId))
            );
        } catch (Exception $exception) {
            logger()->error(
                'Random anime handler',
                [
                    'exception_message' => $exception->getMessage(),
                    'exception_trace'   => $exception->getTraceAsString(),
                ]
            );
        } finally {
            UserStateFacade::resetExecutedCommandsList($chatId);
        }
    }
}
