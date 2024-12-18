<?php

declare(strict_types=1);

namespace App\DTO\Service\Telegram\Anime;

use App\DTO\Service\Telegram\Keyboard\InlineKeyboardButtonDTO;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;

final readonly class AnimeMessageDTO
{
    /**
     * @param list<InlineKeyboardButtonDTO>|list<list<InlineKeyboardButtonDTO>> $inlineKeyboardButtons
     */
    public function __construct(
        public string $photo,
        public string $caption,
        private array $inlineKeyboardButtons = [],
    ) {
    }

    public function generateReplyMarkup(): InlineKeyboardMarkup
    {
        $markup = InlineKeyboardMarkup::make();

        foreach ($this->inlineKeyboardButtons as $button) {
            if (is_array($button)) {
                $row = [];

                foreach ($button as $keyboardButton) {
                    $row[] = InlineKeyboardButton::make(
                        text         : $keyboardButton->text,
                        url          : $keyboardButton->url,
                        callback_data: $keyboardButton->callbackData
                    );
                }

                $markup->addRow(...$row);
                continue;
            }

            $markup->addRow(
                InlineKeyboardButton::make(
                    text         : $button->text,
                    url          : $button->url,
                    callback_data: $button->callbackData
                )
            );
        }

        return $markup;
    }
}
