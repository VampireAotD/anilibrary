<?php

namespace App\Models;

use App\Models\Pivots\AnimeGenre;
use App\Models\Pivots\AnimeTag;
use App\Models\Pivots\AnimeVoiceActing;
use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * Class Anime
 *
 * @package App\Models
 * @property string $id
 * @property string $title
 * @property string $url
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \App\Models\Image|null $image
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\VoiceActing[] $voiceActing
 * @property-read int|null $voice_acting_count
 * @method static \Illuminate\Database\Eloquent\Builder|Anime newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Anime newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Anime query()
 * @method static \Illuminate\Database\Eloquent\Builder|Anime whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Anime whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Anime whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Anime whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Anime whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Anime whereUrl($value)
 * @mixin \Eloquent
 * @property string $status
 * @property float $rating
 * @property string|null $episodes
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Genre[] $genres
 * @property-read int|null $genres_count
 * @method static \Illuminate\Database\Eloquent\Builder|Anime whereEpisodes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Anime whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Anime whereStatus($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Tag[] $tags
 * @property-read int|null $tags_count
 */
class Anime extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'title',
        'url',
        'status',
        'rating',
        'episodes',
    ];

    /**
     * @return BelongsToMany
     */
    public function voiceActing(): BelongsToMany
    {
        return $this->belongsToMany(VoiceActing::class)->using(AnimeVoiceActing::class);
    }

    /**
     * @return MorphOne
     */
    public function image(): MorphOne
    {
        return $this->morphOne(Image::class, 'model');
    }

    /**
     * @return BelongsToMany
     */
    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class, AnimeGenre::getTableName())
            ->using(AnimeGenre::class);
    }

    /**
     * @return BelongsToMany
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, AnimeTag::getTableName())->using(AnimeTag::class);
    }
}
