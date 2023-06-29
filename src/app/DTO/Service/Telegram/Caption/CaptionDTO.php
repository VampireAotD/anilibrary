<?php

declare(strict_types=1);

namespace App\DTO\Service\Telegram\Caption;

/**
 * Class CaptionDTO
 * @package App\DTO\Service\Telegram\Caption
 */
abstract readonly class CaptionDTO
{
    public function __construct(public int $chatId)
    {
    }
}
