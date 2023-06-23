<?php

declare(strict_types=1);

namespace App\DTO\Factory\Telegram\CallbackData;

use App\Enums\Telegram\Callbacks\CallbackQueryTypeEnum;

/**
 * Class CallbackDataDTO
 * @package App\DTO\Factory\Telegram\Callback
 */
abstract readonly class CallbackDataDTO
{
    public function __construct(public CallbackQueryTypeEnum $queryType)
    {
    }
}
