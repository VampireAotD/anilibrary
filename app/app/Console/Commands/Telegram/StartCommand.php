<?php

namespace App\Console\Commands\Telegram;

use App\Models\TelegramUser;
use App\Models\User;
use App\Repositories\Contracts\TelegramUser\Repository;
use Illuminate\Support\Str;
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
    protected static $description = 'Send "/start" or "/s" to get "Hello, World!"';

    /**
     * @var Repository
     */
    private Repository $telegramUserRepository;

    public function __construct(TeleBot $bot, Update $update)
    {
        parent::__construct($bot, $update);

        $this->telegramUserRepository = app(Repository::class);
    }

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

            $telegramUser = TelegramUser::create($data);

            User::create([
                'telegram_user_id' => $telegramUser->id,
                'password' => Str::random(),
            ]);
        }

        try {
            if (!in_array($messageFrom->id, $allowedIds)) {
                $this->sendMessage([
                    'text' => 'Sorry, but you are not allowed to use this bot',
                ]);
            } else {
                $this->sendMessage([
                    'text' => 'Q',
                    'reply_markup' => [
                        'keyboard' => [
                            [
                                [
                                    'text' => 'Add new title'
                                ],
                            ]
                        ],
                        'resize_keyboard' => true,
                    ]
                ]);
            }
        } catch (\Exception $exception) {
            dump($exception->getMessage());
        }
    }
}
