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
        $bot->sendMessage(__('telegram.conversations.search_anime.example'));
        $this->next('search');
    }

    public function search(Nutgram $bot): void
    {
        try {
            $userId    = $bot->userId();
            $response  = $this->animeIndexService->multiMatchSearch($bot->message()?->text);
            $animeList = Arr::get($response, 'hits.hits');

            if (!$animeList) {
                $bot->sendMessage(__('telegram.conversations.search_anime.no_results'));
                $this->end();
                return;
            }

            // Remove previous search results
            $messageId = UserStateFacade::getSearchResultPreview($userId);
            if (!is_null($messageId)) {
                UserStateFacade::removeSearchResultPreview($userId);
                $bot->deleteMessage($userId, (int) $messageId);
            }

            /** @var list<string> $ids */
            $ids = Arr::pluck($animeList, '_source.id');
            UserStateFacade::saveSearchResult($userId, $ids);

            $caption = $this->animeMessageUseCase->generateSearchResult(
                new GenerateAnimeSearchResultDTO($userId)
            );

            $message = $bot->sendPhoto(
                photo       : $caption->photo,
                caption     : $caption->caption,
                reply_markup: $caption->generateReplyMarkup()
            );

            UserStateFacade::saveSearchResultPreview($userId, $message->message_id);
            $this->end();
        } catch (AnimeMessageException $animeMessageException) {
            Log::error('Anime search conversation', [
                'exception_message' => $animeMessageException->getMessage(),
                'exception_trace'   => $animeMessageException->getTraceAsString(),
            ]);

            $bot->sendMessage(__('telegram.conversations.search_anime.no_results'));
            $this->end();
        }
    }
}
