<?php

declare(strict_types=1);

namespace App\Telegram\Commands;

use App\DTO\UseCase\Telegram\Anime\GenerateAnimeMessageDTO;
use App\Enums\Telegram\Actions\ActionEnum;
use App\Exceptions\UseCase\Telegram\AnimeMessageException;
use App\Facades\Telegram\State\UserStateFacade;
use App\Services\AnimeService;
use App\UseCase\Telegram\AnimeMessageUseCase;
use Illuminate\Support\Facades\Log;
use SergiX44\Nutgram\Handlers\Type\Command;
use SergiX44\Nutgram\Nutgram;

final class RandomAnimeCommand extends Command
{
    protected string $command = ActionEnum::RANDOM_ANIME_COMMAND->value;

    protected ?string $description = 'Send "/random" to get random anime.';

    public function handle(Nutgram $bot, AnimeMessageUseCase $animeMessageUseCase, AnimeService $animeService): void
    {
        try {
            $randomAnime = $animeService->randomAnime();

            if (!$randomAnime) {
                $bot->sendMessage(__('telegram.commands.random_anime.unable_to_find_anime'));
                return;
            }

            $message = $animeMessageUseCase->generateAnimeMessage(
                new GenerateAnimeMessageDTO($randomAnime->id)
            );

            $bot->sendPhoto(
                photo  : $message->photo,
                caption: $message->caption,
            );
        } catch (AnimeMessageException $exception) {
            Log::error('Random anime command', [
                'exception_message' => $exception->getMessage(),
                'exception_trace'   => $exception->getTraceAsString(),
            ]);
        } finally {
            UserStateFacade::resetExecutedCommandsList($bot->userId());
        }
    }
}