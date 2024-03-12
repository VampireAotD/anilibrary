<?php

declare(strict_types=1);

namespace App\Telegram\Callbacks;

use App\DTO\UseCase\Telegram\Anime\GenerateAnimeListDTO;
use App\Enums\Telegram\Callbacks\CallbackDataTypeEnum;
use App\Exceptions\UseCase\Telegram\AnimeMessageException;
use App\UseCase\Telegram\AnimeMessageUseCase;
use Illuminate\Support\Facades\Log;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Input\InputMediaPhoto;

final readonly class AnimeListCallback implements CallbackInterface
{
    public function __construct(private AnimeMessageUseCase $animeMessageUseCase)
    {
    }

    public static function command(): string
    {
        $command = CallbackDataTypeEnum::ANIME_LIST->value;
        return "command=({$command})&page=(\d+)";
    }

    public function __invoke(Nutgram $bot, string ...$arguments): void
    {
        [, $page] = $arguments;

        try {
            $pagination = $this->animeMessageUseCase->generateAnimeList(
                new GenerateAnimeListDTO((int) $page)
            );

            $bot->editMessageMedia(
                new InputMediaPhoto(
                    media           : $pagination->photo,
                    caption         : $pagination->caption,
                    parse_mode      : null,
                    caption_entities: null,
                    has_spoiler     : false
                ),
                reply_markup: $pagination->generateReplyMarkup(),
            );
        } catch (AnimeMessageException $exception) {
            Log::error('Anime list callback', [
                'exception_message' => $exception->getMessage(),
                'exception_trace'   => $exception->getTraceAsString(),
            ]);

            $bot->sendMessage(__('telegram.callbacks.anime_list.render_error'));
        }
    }
}
