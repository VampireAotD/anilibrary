<?php

declare(strict_types=1);

namespace App\Models\Pivots;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @mixin IdeHelperAnimeVoiceActing
 */
class AnimeVoiceActing extends Pivot
{
    use HasUuids;

    protected $hidden = ['pivot'];
}
