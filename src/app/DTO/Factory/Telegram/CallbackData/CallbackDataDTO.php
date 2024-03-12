<?php

declare(strict_types=1);

namespace App\DTO\Factory\Telegram\CallbackData;

use App\Enums\Telegram\Callbacks\CallbackDataTypeEnum;

abstract readonly class CallbackDataDTO
{
    public function __construct(public CallbackDataTypeEnum $queryType)
    {
    }
}
