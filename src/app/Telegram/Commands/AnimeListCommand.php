<?php

declare(strict_types=1);

namespace App\Telegram\Commands;

use App\DTO\UseCase\Telegram\Anime\GenerateAnimeListDTO;
use App\Enums\Telegram\Actions\ActionEnum;
use App\Exceptions\UseCase\Telegram\AnimeMessageException;
use App\UseCase\Telegram\AnimeMessageUseCase;
use Illuminate\Support\Facades\Log;
use SergiX44\Nutgram\Handlers\Type\Command;
use SergiX44\Nutgram\Nutgram;

final class AnimeListCommand extends Command
{
    protected string $command = ActionEnum::ANIME_LIST_COMMAND->value;

    protected ?string $description = 'Send "/list" to get list of available anime.';

    public function handle(Nutgram $bot, AnimeMessageUseCase $animeMessageUseCase): void
    {
        try {
            $pagination = $animeMessageUseCase->generateAnimeList(new GenerateAnimeListDTO());

            $bot->sendPhoto(
                photo       : $pagination->photo,
                caption     : $pagination->caption,
                reply_markup: $pagination->generateReplyMarkup(),
            );
        } catch (AnimeMessageException $exception) {
            Log::error('Anime list command', [
                'exception_message' => $exception->getMessage(),
                'exception_trace'   => $exception->getTraceAsString(),
            ]);
        }
    }
}
