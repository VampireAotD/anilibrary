<?php

declare(strict_types=1);

namespace App\Enums\Validation\Scraper;

/**
 * Class EncodedImageRuleEnum
 * @package App\Enums\Validation\Scraper
 */
enum EncodedImageRuleEnum : string
{
    case INVALID_ENCODING = 'Image not properly encoded';
}
