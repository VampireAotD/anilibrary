<?php

declare(strict_types=1);

namespace App\Telegram\Callbacks;

use App\DTO\UseCase\Telegram\Anime\GenerateAnimeMessageDTO;
use App\Enums\Telegram\Callbacks\CallbackDataTypeEnum;
use App\Exceptions\UseCase\Telegram\AnimeMessageException;
use App\UseCase\Telegram\AnimeMessageUseCase;
use Illuminate\Support\Facades\Log;
use SergiX44\Nutgram\Nutgram;

final readonly class ViewAnimeCallback implements CallbackInterface
{
    public function __construct(private AnimeMessageUseCase $animeMessageUseCase)
    {
    }

    public static function command(): string
    {
        $command = CallbackDataTypeEnum::VIEW_ANIME->value;
        return sprintf('command=(%s)&animeId=(\w+)', $command);
    }

    public function __invoke(Nutgram $bot, string ...$arguments): void
    {
        [, $animeId] = $arguments;

        try {
            $message = $this->animeMessageUseCase->generateAnimeMessage(
                new GenerateAnimeMessageDTO($animeId, true)
            );

            $bot->sendPhoto(
                photo  : $message->photo,
                caption: $message->caption,
            );
        } catch (AnimeMessageException $animeMessageException) {
            Log::error('View anime callback', [
                'exception_message' => $animeMessageException->getMessage(),
                'exception_trace'   => $animeMessageException->getTraceAsString(),
            ]);

            $bot->sendMessage(__('telegram.callbacks.view_anime.render_error'));
        }
    }
}
