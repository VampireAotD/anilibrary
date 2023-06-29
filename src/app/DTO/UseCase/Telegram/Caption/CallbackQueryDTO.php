<?php

declare(strict_types=1);

namespace App\DTO\UseCase\Telegram\Caption;

use App\Enums\Telegram\Callbacks\CallbackQueryTypeEnum;

/**
 * Class CallbackQueryDTO
 * @package App\DTO\UseCase\Telegram\Caption
 */
abstract readonly class CallbackQueryDTO
{
    public function __construct(public int $chatId, public CallbackQueryTypeEnum $queryType)
    {
    }
}
