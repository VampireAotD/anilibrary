<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Pivots\AnimeGenre;
use App\Models\Pivots\AnimeVoiceActing;
use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * App\Models\Anime
 *
 * @property string                                                                       $id
 * @property string                                                                       $title
 * @property \Illuminate\Support\Carbon|null                                              $created_at
 * @property \Illuminate\Support\Carbon|null                                              $updated_at
 * @property string|null                                                                  $deleted_at
 * @property string                                                                       $status
 * @property float                                                                        $rating
 * @property string|null                                                                  $episodes
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Genre>        $genres
 * @property-read int|null                                                                $genres_count
 * @property-read \App\Models\Image|null                                                  $image
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AnimeSynonym> $synonyms
 * @property-read int|null                                                                $synonyms_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AnimeUrl>     $urls
 * @property-read int|null                                                                $urls_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\VoiceActing>  $voiceActing
 * @property-read int|null                                                                $voice_acting_count
 * @property-read Attribute                                                               $toTelegramCaption
 * @method static \Database\Factories\AnimeFactory            factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Anime newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Anime newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Anime query()
 * @method static \Illuminate\Database\Eloquent\Builder|Anime whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Anime whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Anime whereEpisodes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Anime whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Anime whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Anime whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Anime whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Anime whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Anime filter(array $filters)
 * @mixin \Eloquent
 */
class Anime extends Model
{
    use HasUuids;
    use HasFactory;
    use Filterable;

    protected $fillable = [
        'title',
        'status',
        'rating',
        'episodes',
    ];

    protected $casts = [
        'id' => 'string',
    ];

    /**
     * @return BelongsToMany
     */
    public function voiceActing(): BelongsToMany
    {
        return $this->belongsToMany(VoiceActing::class)
                    ->using(AnimeVoiceActing::class);
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
     * @return HasMany
     */
    public function synonyms(): HasMany
    {
        return $this->hasMany(AnimeSynonym::class);
    }

    /**
     * @return HasMany
     */
    public function urls(): HasMany
    {
        return $this->hasMany(AnimeUrl::class);
    }

    /**
     * @return Attribute
     */
    public function toTelegramCaption(): Attribute
    {
        return Attribute::make(
            fn() => sprintf(
                "Название: %s\nСтатус: %s\nЭпизоды: %s\nОценка: %s\nОзвучки: %s\nЖанры: %s",
                $this->title,
                $this->status,
                $this->episodes,
                $this->rating,
                $this->voiceActing->implode('name', ', '),
                $this->genres->implode('name', ', '),
            )
        );
    }
}
