<?php

declare(strict_types=1);

namespace App\DTO\UseCase\Telegram\CallbackQuery;

use App\Enums\Telegram\CallbackQueryTypeEnum;

/**
 * Class CallbackQueryDTO
 * @package App\DTO\UseCase\Telegram\CallbackQuery
 */
abstract readonly class CallbackQueryDTO
{
    public function __construct(public int $chatId, public CallbackQueryTypeEnum $queryType)
    {
    }
}
