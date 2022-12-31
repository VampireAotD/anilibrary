<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\AnimeUrl
 *
 * @property string                          $anime_id
 * @property string                          $url
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Anime          $anime
 * @method static \Database\Factories\AnimeUrlFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|AnimeUrl newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AnimeUrl newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AnimeUrl query()
 * @method static \Illuminate\Database\Eloquent\Builder|AnimeUrl whereAnimeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnimeUrl whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnimeUrl whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnimeUrl whereUrl($value)
 * @mixin \Eloquent
 */
class AnimeUrl extends Model
{
    use HasFactory;

    protected $fillable = ['anime_id', 'url'];

    /**
     * @return BelongsTo
     */
    public function anime(): BelongsTo
    {
        return $this->belongsTo(Anime::class);
    }
}
