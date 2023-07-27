<?php

declare(strict_types=1);

namespace App\Telegram\Handlers;

use App\DTO\UseCase\Telegram\Caption\PaginationDTO;
use App\Enums\Telegram\Callbacks\CallbackQueryTypeEnum;
use App\Enums\Telegram\Commands\CommandEnum;
use App\Enums\Telegram\Handlers\AnimeSearchHandlerEnum;
use App\Facades\Telegram\State\UserStateFacade;
use App\Services\Elasticsearch\Index\AnimeIndexService;
use App\UseCase\Telegram\CaptionUseCase;
use Exception;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Support\Arr;
use WeStacks\TeleBot\Objects\Message;
use WeStacks\TeleBot\Objects\Update;
use WeStacks\TeleBot\TeleBot;

/**
 * Class AnimeSearchHandler
 * @package App\Telegram\Handlers
 */
final class AnimeSearchHandler extends TextMessageUpdateHandler
{
    protected array $allowedMessages = [
        CommandEnum::ANIME_SEARCH_COMMAND->value,
        CommandEnum::ANIME_SEARCH_BUTTON->value,
    ];

    private AnimeIndexService $animeIndexService;
    private CaptionUseCase    $callbackQueryUseCase;

    public function __construct(TeleBot $bot, Update $update)
    {
        parent::__construct($bot, $update);

        $this->animeIndexService    = app(AnimeIndexService::class);
        $this->callbackQueryUseCase = app(CaptionUseCase::class);
    }

    /**
     * @return PromiseInterface|Message|void
     */
    public function handle()
    {
        $chatId = $this->update->chat()->id;

        try {
            $response  = $this->animeIndexService->multiMatchSearch($this->update->message->text);
            $animeList = Arr::get($response, 'hits.hits');

            if (!$animeList) {
                return $this->sendMessage(['text' => AnimeSearchHandlerEnum::NO_SEARCH_RESULTS->value]);
            }

            if ($messageId = UserStateFacade::getSearchResultPreview($chatId)) {
                UserStateFacade::removeSearchResultPreview($chatId);
                $this->deleteMessage(['message_id' => $messageId]);
            }

            $ids = array_filter(Arr::pluck($animeList, '_source.id'));
            UserStateFacade::saveSearchResult($chatId, $ids);

            $caption = $this->callbackQueryUseCase->createSearchPaginationCaption(
                new PaginationDTO($chatId, queryType: CallbackQueryTypeEnum::SEARCH_LIST)
            );

            if (!$caption) {
                return $this->sendMessage(['text' => AnimeSearchHandlerEnum::NO_SEARCH_RESULTS->value]);
            }

            $message = $this->sendPhoto($caption);

            UserStateFacade::resetExecutedCommandsList($chatId);
            UserStateFacade::saveSearchResultPreview($chatId, $message->message_id);

            return $message;
        } catch (Exception $exception) {
            logger()->error('Anime list handler', [
                'exception_message' => $exception->getMessage(),
                'exception_trace'   => $exception->getTraceAsString(),
            ]);

            UserStateFacade::resetExecutedCommandsList($chatId);
        }
    }
}
