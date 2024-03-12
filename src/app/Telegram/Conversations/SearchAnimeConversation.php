<?php

declare(strict_types=1);

namespace App\Telegram\Conversations;

use App\DTO\UseCase\Telegram\Anime\GenerateAnimeSearchResultDTO;
use App\Exceptions\UseCase\Telegram\AnimeMessageException;
use App\Facades\Telegram\State\UserStateFacade;
use App\Services\Elasticsearch\Index\AnimeIndexService;
use App\UseCase\Telegram\AnimeMessageUseCase;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use SergiX44\Nutgram\Conversations\Conversation;
use SergiX44\Nutgram\Nutgram;

final class SearchAnimeConversation extends Conversation
{
    public function __construct(
        private readonly AnimeIndexService   $animeIndexService,
        private readonly AnimeMessageUseCase $animeMessageUseCase
    ) {
    }

    public function start(Nutgram $bot): void
    {
        $bot->sendMessage(__('telegram.commands.search_anime.example'));
        $this->next('search');
    }

    public function search(Nutgram $bot): void
    {
        $userId = $bot->userId();

        try {
            $response  = $this->animeIndexService->multiMatchSearch($bot->message()?->text);
            $animeList = Arr::get($response, 'hits.hits');

            if (!$animeList) {
                $bot->sendMessage(__('telegram.commands.search_anime.no_results'));
                $this->end();
                return;
            }

            // Remove previous search results
            if ($messageId = UserStateFacade::getSearchResultPreview($userId)) {
                UserStateFacade::removeSearchResultPreview($userId);
                $bot->deleteMessage($userId, (int) $messageId);
            }

            $ids = array_filter(Arr::pluck($animeList, '_source.id'));
            UserStateFacade::saveSearchResult($userId, $ids);

            $caption = $this->animeMessageUseCase->generateSearchResult(
                new GenerateAnimeSearchResultDTO($userId)
            );

            $message = $bot->sendPhoto(
                photo       : $caption->photo,
                caption     : $caption->caption,
                reply_markup: $caption->generateReplyMarkup()
            );

            UserStateFacade::resetExecutedCommandsList($userId);
            UserStateFacade::saveSearchResultPreview($userId, $message->message_id);
            $this->end();
        } catch (AnimeMessageException $exception) {
            Log::error('Anime search conversation', [
                'exception_message' => $exception->getMessage(),
                'exception_trace'   => $exception->getTraceAsString(),
            ]);

            $bot->sendMessage(__('telegram.commands.search_anime.no_results'));
            UserStateFacade::resetExecutedCommandsList($userId);
            $this->end();
        }
    }
}
