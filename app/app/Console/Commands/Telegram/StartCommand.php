<?php

namespace App\Console\Commands\Telegram;

use App\Enums\KeyboardEnum;
use App\Repositories\Contracts\TelegramUser\Repository;
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
     * @var Repository
     */
    private Repository $telegramUserRepository;

    /**
     * @var TelegramUserService
     */
    private TelegramUserService $telegramUserService;

    public function __construct(TeleBot $bot, Update $update)
    {
        parent::__construct($bot, $update);

        $this->telegramUserRepository = app(Repository::class);
        $this->telegramUserService = app(TelegramUserService::class);
    }

    private const DENIAL_MESSAGE = "\xF0\x9F\x98\xBF К сожалению, у Вас нету полномочий пользоваться данным ботом";

    private const WELCOME_MESSAGE = "\xF0\x9F\x91\x8B Вас приветствует AniLibrary Bot!
    Пожалуйста, выберете интересующее Вас действие:";

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $allowedIds = explode(',', config('telebot.allowedTelegramIds'));

        $messageFrom = $this->update->message->from;

        if (!$this->telegramUserRepository->findByTelegramId($messageFrom->id)) {
            $data = $messageFrom->toArray();
            $data['telegram_id'] = $data['id'];

            $this->telegramUserService->register($data);
        }

        try {
            if (!in_array($messageFrom->id, $allowedIds)) {
                $this->sendMessage([
                    'text' => self::DENIAL_MESSAGE,
                ]);
            } else {
                $this->sendMessage([
                    'text' => self::WELCOME_MESSAGE,
                    'reply_markup' => [
                        'keyboard' => [
                            [
                                [
                                    'text' => KeyboardEnum::ADD_NEW_TITLE->value,
                                ],
                                [
                                    'text' => KeyboardEnum::RANDOM_ANIME->value,
                                ],
                            ]
                        ],
                        'resize_keyboard' => true,
                    ]
                ]);
            }
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
