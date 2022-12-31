<?php

declare(strict_types=1);

namespace App\Models\Pivots;

use App\Models\Traits\CanProvideTableName;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * App\Models\Pivots\AnimeTag
 *
 * @property string $id
 * @property string $anime_id
 * @property string $tag_id
 * @method static \Illuminate\Database\Eloquent\Builder|AnimeTag newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AnimeTag newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AnimeTag query()
 * @method static \Illuminate\Database\Eloquent\Builder|AnimeTag whereAnimeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnimeTag whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnimeTag whereTagId($value)
 * @mixin \Eloquent
 */
class AnimeTag extends Pivot
{
    use HasUuids, CanProvideTableName;

    protected $table = 'anime_tags';
}
