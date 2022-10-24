<?php

declare(strict_types=1);

namespace App\Telegram\Commands;

use App\Enums\Telegram\CommandEnum;
use App\Repositories\Contracts\TelegramUser\TelegramUserRepositoryInterface;
use App\Services\TelegramUserService;
use WeStacks\TeleBot\Handlers\CommandHandler;
use WeStacks\TeleBot\Objects\Update;
use WeStacks\TeleBot\TeleBot;

/**
 * Class StartCommand
 * @package App\Console\Commands\Telegram
 */
class StartCommand extends CommandHandler
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected static $aliases = ['/start', '/s'];

    /**
     * The console command description.
     *
     * @var string
     */
    protected static $description = 'Send "/start" or "/s" to get description of what this bot can do';

    /**
     * @var TelegramUserRepositoryInterface
     */
    private TelegramUserRepositoryInterface $telegramUserRepository;

    /**
     * @var TelegramUserService
     */
    private TelegramUserService $telegramUserService;

    private const WELCOME_MESSAGE = "Вас приветствует AniLibrary Bot!\xF0\x9F\x91\x8B\nПожалуйста, выберете интересующее Вас действие:";

    public function __construct(TeleBot $bot, Update $update)
    {
        parent::__construct($bot, $update);

        $this->telegramUserRepository = app(TelegramUserRepositoryInterface::class);
        $this->telegramUserService    = app(TelegramUserService::class);
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $messageFrom = $this->update->message->from;

        if (!$this->telegramUserRepository->findByTelegramId($messageFrom->id)) {
            $data                = $messageFrom->toArray();
            $data['telegram_id'] = $data['id'];

            $this->telegramUserService->register($data);
        }

        try {
            $this->sendMessage(
                [
                    'text'         => self::WELCOME_MESSAGE,
                    'reply_markup' => [
                        'keyboard'        => [
                            [
                                [
                                    'text' => CommandEnum::ADD_NEW_TITLE->value,
                                ],
                                [
                                    'text' => CommandEnum::RANDOM_ANIME->value,
                                ],
                            ],
                            [
                                [
                                    'text' => CommandEnum::ANIME_LIST->value,
                                ],
                            ],
                        ],
                        'resize_keyboard' => true,
                    ],
                ]
            );
        } catch (\Exception $exception) {
            logger()->channel('single')->warning(
                $exception->getMessage(),
                [
                    'exceptionTrace' => $exception->getTraceAsString(),
                ]
            );
        }
    }
}
