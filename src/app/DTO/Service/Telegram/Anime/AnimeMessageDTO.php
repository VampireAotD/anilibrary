<?php

declare(strict_types=1);

namespace App\DTO\Service\Telegram\Anime;

use App\DTO\Service\Telegram\Keyboard\InlineKeyboardButtonDTO;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;

final class AnimeMessageDTO
{
    public function __construct(
        public readonly string $photo,
        public readonly string $caption,
        /** @var InlineKeyboardButtonDTO[] */
        private array          $inlineKeyboardButtons = [],
    ) {
    }

    public function addButton(InlineKeyboardButtonDTO $dto): void
    {
        $this->inlineKeyboardButtons[] = $dto;
    }

    public function generateReplyMarkup(): InlineKeyboardMarkup
    {
        $markup = InlineKeyboardMarkup::make();

        foreach ($this->inlineKeyboardButtons as $keyboardButton) {
            $markup->addRow(
                InlineKeyboardButton::make(
                    text         : $keyboardButton->text,
                    url          : $keyboardButton->url,
                    callback_data: $keyboardButton->callbackData
                )
            );
        }

        return $markup;
    }
}
