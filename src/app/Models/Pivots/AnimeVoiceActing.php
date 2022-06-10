<?php

declare(strict_types=1);

namespace App\Models\Pivots;

use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * App\Models\AnimeVoiceActing
 *
 * @property string $id
 * @property string $anime_id
 * @property string $voice_acting_id
 * @method static \Illuminate\Database\Eloquent\Builder|AnimeVoiceActing newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AnimeVoiceActing newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AnimeVoiceActing query()
 * @method static \Illuminate\Database\Eloquent\Builder|AnimeVoiceActing whereAnimeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnimeVoiceActing whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnimeVoiceActing whereVoiceActingId($value)
 * @mixin \Eloquent
 */
class AnimeVoiceActing extends Pivot
{
    use HasUuid;

    protected $hidden = ['pivot'];
}
