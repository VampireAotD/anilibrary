<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * enum VoiceActingSeederEnum
 * @package App\Enums
 */
enum VoiceActingSeederEnum: string
{
    case ANIDUB = 'AniDUB';
    case ANILIBRIA = 'AniLibria';
    case STUDIO_BAND = 'Студийная Банда';
    case ANIMEVOST = 'AnimeVost';
    case DREAM_CAST = 'Dream Cast';
}
