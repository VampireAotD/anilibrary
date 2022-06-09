<?php

declare(strict_types=1);

namespace App\Dto\Handlers;

/**
 * Class CallbackDataDTO
 * @package App\DTO\Handlers
 */
class CallbackDataDTO
{
    /**
     * @param string|null $animeId
     * @param int|null    $pageNumber
     */
    public function __construct(
        public readonly ?string $animeId = null,
        public readonly ?int    $pageNumber = null
    ) {
    }
}
