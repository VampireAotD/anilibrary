<?php

declare(strict_types=1);

namespace App\Telegram\Callbacks;

use App\DTO\UseCase\Telegram\Anime\GenerateAnimeSearchResultDTO;
use App\Enums\Telegram\Callbacks\CallbackDataTypeEnum;
use App\Exceptions\UseCase\Telegram\AnimeMessageException;
use App\UseCase\Telegram\AnimeMessageUseCase;
use Illuminate\Support\Facades\Log;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Input\InputMediaPhoto;

final readonly class AnimeSearchCallback implements CallbackInterface
{
    public function __construct(private AnimeMessageUseCase $animeMessageUseCase)
    {
    }

    public static function command(): string
    {
        $command = CallbackDataTypeEnum::SEARCH_LIST->value;
        return sprintf('command=(%s)&page=(\d+)', $command);
    }

    public function __invoke(Nutgram $bot, string ...$arguments): void
    {
        [, $page] = $arguments;

        try {
            $pagination = $this->animeMessageUseCase->generateSearchResult(
                new GenerateAnimeSearchResultDTO($bot->userId(), (int) $page)
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
        } catch (AnimeMessageException $animeMessageException) {
            Log::error('Anime search callback', [
                'exception_message' => $animeMessageException->getMessage(),
                'exception_trace'   => $animeMessageException->getTraceAsString(),
            ]);

            $bot->sendMessage(__('telegram.callbacks.anime_search.render_error'));
        }
    }
}
