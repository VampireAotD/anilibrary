<?php

declare(strict_types=1);

namespace App\Models\Pivots;

use App\Models\Anime;
use App\Models\VoiceActing;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @mixin IdeHelperAnimeVoiceActing
 */
class AnimeVoiceActing extends Pivot
{
    use HasUuids;

    protected $hidden = ['pivot'];

    /**
     * @return BelongsTo<Anime, $this>
     */
    public function anime(): BelongsTo
    {
        return $this->belongsTo(Anime::class);
    }

    /**
     * @return BelongsTo<VoiceActing, $this>
     */
    public function voiceActing(): BelongsTo
    {
        return $this->belongsTo(VoiceActing::class);
    }
}
