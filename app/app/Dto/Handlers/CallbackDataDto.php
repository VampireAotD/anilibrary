<?php

namespace App\Dto\Handlers;

/**
 * Class CallbackDataDto
 * @package App\Dto\Handlers
 */
class CallbackDataDto
{
    public function __construct(
        public readonly ?string $animeId = null,
        public readonly ?int $pageNumber = null
    ) {
    }
}
