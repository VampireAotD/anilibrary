<?php

declare(strict_types=1);

namespace App\Telegram\Handlers;

use App\DTO\UseCase\Telegram\Caption\PaginationDTO;
use App\Enums\Telegram\Commands\CommandEnum;
use App\Facades\Telegram\State\UserStateFacade;
use App\UseCase\Telegram\CaptionUseCase;
use Exception;
use GuzzleHttp\Promise\PromiseInterface;
use WeStacks\TeleBot\Objects\Message;
use WeStacks\TeleBot\Objects\Update;
use WeStacks\TeleBot\TeleBot;

/**
 * Class AnimeListHandler
 * @package App\Telegram\Handlers
 */
final class AnimeListHandler extends TextMessageUpdateHandler
{
    protected array $allowedMessages = [CommandEnum::ANIME_LIST_COMMAND->value, CommandEnum::ANIME_LIST_BUTTON->value];

    private CaptionUseCase $callbackQueryUseCase;

    public function __construct(TeleBot $bot, Update $update)
    {
        parent::__construct($bot, $update);

        $this->callbackQueryUseCase = app(CaptionUseCase::class);
    }

    /**
     * @return PromiseInterface|Message|void
     */
    public function handle()
    {
        $chatId = $this->update->chat()->id;

        try {
            $caption = $this->callbackQueryUseCase->createPaginationCaption(new PaginationDTO($chatId));

            return $this->sendPhoto($caption);
        } catch (Exception $exception) {
            logger()->error('Anime list handler', [
                'exception_message' => $exception->getMessage(),
                'exception_trace'   => $exception->getTraceAsString(),
            ]);
        } finally {
            UserStateFacade::resetExecutedCommandsList($chatId);
        }
    }
}
