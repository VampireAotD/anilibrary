<?php

declare(strict_types=1);

namespace App\Enums;

use App\Enums\Traits\CanProvideCasesValues;

enum VoiceActingEnum: string
{
    use CanProvideCasesValues;

    case ANIDUB      = 'AniDUB';
    case ANILIBRIA   = 'AniLibria';
    case STUDIO_BAND = 'Студийная Банда';
    case ANIMEVOST   = 'AnimeVost';
    case DREAM_CAST  = 'Dream Cast';
}
