<?php

declare(strict_types=1);

namespace App\Telegram\Commands;

use App\DTO\Service\Telegram\CreateUserDTO;
use App\Enums\Telegram\Commands\CommandEnum;
use App\Enums\Telegram\Commands\StartCommandEnum;
use App\Jobs\Telegram\CreateUserJob;
use GuzzleHttp\Promise\PromiseInterface;
use WeStacks\TeleBot\Handlers\CommandHandler;
use WeStacks\TeleBot\Objects\Message;

/**
 * Class StartCommand
 * @package App\Console\Commands\Telegram
 */
class StartCommand extends CommandHandler
{
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
        $user = $this->update->user();

        if ($user && !$user->is_bot) {
            /** @phpstan-ignore-next-line */
            $dto = new CreateUserDTO($user->id, $user?->first_name, $user?->last_name ?? 'not set', $user?->username);

            CreateUserJob::dispatch($dto);
        }

        return $this->sendMessage([
            'text'         => StartCommandEnum::WELCOME_MESSAGE->value,
            'reply_markup' => [
                'keyboard'        => [
                    [
                        [
                            'text' => CommandEnum::ADD_ANIME_BUTTON->value,
                        ],
                        [
                            'text' => CommandEnum::RANDOM_ANIME_BUTTON->value,
                        ],
                    ],
                    [
                        [
                            'text' => CommandEnum::ANIME_LIST_BUTTON->value,
                        ],
                        [
                            'text' => CommandEnum::ANIME_SEARCH_BUTTON->value,
                        ],
                    ],
                ],
                'resize_keyboard' => true,
            ],
        ]);
    }
}
