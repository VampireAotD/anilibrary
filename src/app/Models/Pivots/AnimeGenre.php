<?php

declare(strict_types=1);

namespace App\Models\Pivots;

use App\Models\Concerns\ProvideTableName;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @mixin IdeHelperAnimeGenre
 */
class AnimeGenre extends Pivot
{
    use HasUuids;
    use ProvideTableName;

    protected $table = 'anime_genres';

    protected $hidden = ['pivot'];
}
