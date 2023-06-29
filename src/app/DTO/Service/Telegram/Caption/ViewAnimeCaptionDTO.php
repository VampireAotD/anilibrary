<?php

declare(strict_types=1);

namespace App\DTO\Service\Telegram\Caption;

use App\Models\Anime;

/**
 * Class ViewAnimeCaptionDTO
 * @package App\DTO\Service\Telegram\Caption
 */
final readonly class ViewAnimeCaptionDTO extends CaptionDTO
{
    public function __construct(public Anime $anime, int $chatId)
    {
        parent::__construct($chatId);
    }
}
