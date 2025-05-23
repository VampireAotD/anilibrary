<?php

declare(strict_types=1);

namespace App\Telegram\Commands;

use App\DTO\Service\Telegram\User\TelegramUserDTO;
use App\Enums\Telegram\Actions\ActionEnum;
use App\Enums\Telegram\Buttons\CommandButtonEnum;
use App\Jobs\Telegram\RegisterTelegramUserJob;
use SergiX44\Nutgram\Handlers\Type\Command;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Keyboard\KeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\ReplyKeyboardMarkup;

final class StartCommand extends Command
{
    protected string $command = ActionEnum::START_COMMAND->value;

    protected ?string $description = 'Send "/start" to get description of what this bot can do.';

    public function handle(Nutgram $bot): void
    {
        $user = $bot->user();

        if ($user && !$user->is_bot) {
            RegisterTelegramUserJob::dispatch(
                new TelegramUserDTO(
                    telegramId: $user->id,
                    firstName : $user->first_name,
                    lastName  : $user->last_name,
                    username  : $user->username
                )
            );
        }

        $bot->sendMessage(
            text        : __('telegram.commands.start.welcome_message'),
            reply_markup: $this->createReplyMarkup(CommandButtonEnum::cases())
        );
    }

    /**
     * @param list<CommandButtonEnum> $buttons
     * @param positive-int            $buttonsPerRow
     */
    private function createReplyMarkup(array $buttons, int $buttonsPerRow = 2): ReplyKeyboardMarkup
    {
        $markup       = ReplyKeyboardMarkup::make(resize_keyboard: true);
        $buttonChunks = array_chunk($buttons, $buttonsPerRow);

        foreach ($buttonChunks as $chunk) {
            $row = array_map(static fn($buttonEnum) => KeyboardButton::make($buttonEnum->value), $chunk);

            $markup->addRow(...$row);
        }

        return $markup;
    }
}
