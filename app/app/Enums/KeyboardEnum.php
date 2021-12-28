<?php

namespace App\Enums;

use App\Enums\Traits\CanProvideCasesValues;

/**
 * Enum KeyboardEnum
 * @package App\Enums
 */
enum KeyboardEnum: string
{
    use CanProvideCasesValues;

    case ADD_NEW_TITLE = "\xE2\x9E\x95 Добавить новое аниме";
    case RANDOM_ANIME = "\xE2\x81\x89 Рандомное аниме";
}
