<?php

declare(strict_types=1);

namespace App\Models\Pivots;

use App\Models\Anime;
use App\Models\Genre;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @mixin IdeHelperAnimeGenre
 */
class AnimeGenre extends Pivot
{
    use HasUuids;

    protected $hidden = ['pivot'];

    public function anime(): BelongsTo
    {
        return $this->belongsTo(Anime::class);
    }

    public function genre(): BelongsTo
    {
        return $this->belongsTo(Genre::class);
    }
}
