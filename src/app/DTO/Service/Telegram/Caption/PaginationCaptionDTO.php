<?php

declare(strict_types=1);

namespace App\DTO\Service\Telegram\Caption;

use App\Enums\Telegram\Callbacks\CallbackQueryTypeEnum;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class PaginationCaptionDTO
 * @package App\DTO\Service\Telegram\Caption
 */
final readonly class PaginationCaptionDTO extends CaptionDTO
{
    public function __construct(
        public LengthAwarePaginator  $paginator,
        int                          $chatId,
        public int                   $page = 1,
        public CallbackQueryTypeEnum $queryType = CallbackQueryTypeEnum::ANIME_LIST
    ) {
        parent::__construct($chatId);
    }
}
