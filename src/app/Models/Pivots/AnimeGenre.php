<?php

declare(strict_types=1);

namespace App\Models\Pivots;

use App\Models\Traits\CanProvideTableName;
use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * App\Models\AnimeGenre
 *
 * @property string $id
 * @property string $anime_id
 * @property string $genre_id
 * @method static \Illuminate\Database\Eloquent\Builder|AnimeGenre newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AnimeGenre newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AnimeGenre query()
 * @method static \Illuminate\Database\Eloquent\Builder|AnimeGenre whereAnimeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnimeGenre whereGenreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnimeGenre whereId($value)
 * @mixin \Eloquent
 */
class AnimeGenre extends Pivot
{
    use HasUuid, CanProvideTableName;

    protected $table = 'anime_genres';

    protected $hidden = ['pivot'];
}
