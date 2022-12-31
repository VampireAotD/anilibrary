<?php

declare(strict_types=1);

namespace App\Telegram\Commands;

use App\Enums\Telegram\CommandEnum;
use GuzzleHttp\Promise\PromiseInterface;
use WeStacks\TeleBot\Handlers\CommandHandler;
use WeStacks\TeleBot\Objects\Message;

/**
 * Class StartCommand
 * @package App\Console\Commands\Telegram
 */
class StartCommand extends CommandHandler
{
    private const WELCOME_MESSAGE = "Вас приветствует AniLibrary Bot!\xF0\x9F\x91\x8B\nПожалуйста, выберете интересующее Вас действие:";

    /**
     * The name and signature of the console command.
     *
     * @var string[]
     */
    protected static $aliases = ['/start', '/s'];

    /**
     * The console command description.
     *
     * @var string
     */
    protected static $description = 'Send "/start" or "/s" to get description of what this bot can do';

    /**
     * @return Message|PromiseInterface
     */
    public function handle(): Message | PromiseInterface
    {
        return $this->sendMessage(
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
    }
}
