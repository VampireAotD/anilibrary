<?php

namespace App\Dto\Handlers;

/**
 * Class CallbackDataDto
 * @package App\Dto\Handlers
 */
class CallbackDataDto
{
    /**
     * @param string|null $animeId
     * @param int|null $pageNumber
     */
    public function __construct(
        public readonly ?string $animeId = null,
        public readonly ?int $pageNumber = null
    ) {
    }
}
