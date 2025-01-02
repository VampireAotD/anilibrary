<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Anime\StatusEnum;
use App\Enums\Anime\TypeEnum;
use App\Models\Concerns\Filterable;
use App\Models\Concerns\HasImage;
use App\Models\Pivots\AnimeGenre;
use App\Models\Pivots\AnimeVoiceActing;
use App\Models\Pivots\UserAnimeList;
use App\Observers\AnimeObserver;
use Database\Factories\AnimeFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @mixin IdeHelperAnime
 */
#[ObservedBy(AnimeObserver::class)]
class Anime extends Model
{
    use HasUuids;
    /** @use HasFactory<AnimeFactory> */
    use HasFactory;
    use HasImage;
    use Filterable;
    use SoftDeletes;

    protected $fillable = [
        'title',
        'status',
        'rating',
        'episodes',
        'type',
        'year',
    ];

    /**
     * @psalm-suppress InvalidReturnStatement, InvalidReturnType Suppressed because of conflict with PHPStan parser
     * @return array{id: 'string', type: 'App\Enums\Anime\TypeEnum', status: 'App\Enums\Anime\StatusEnum'}
     */
    protected function casts(): array
    {
        return [
            'id'     => 'string',
            'type'   => TypeEnum::class,
            'status' => StatusEnum::class,
        ];
    }

    /**
     * @return HasMany<AnimeUrl, $this>
     */
    public function urls(): HasMany
    {
        return $this->hasMany(AnimeUrl::class);
    }

    /**
     * @return HasMany<AnimeSynonym, $this>
     */
    public function synonyms(): HasMany
    {
        return $this->hasMany(AnimeSynonym::class);
    }

    /**
     * @return BelongsToMany<VoiceActing, $this>
     */
    public function voiceActing(): BelongsToMany
    {
        return $this->belongsToMany(VoiceActing::class)->using(AnimeVoiceActing::class);
    }

    /**
     * @return BelongsToMany<Genre, $this>
     */
    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class)->using(AnimeGenre::class);
    }

    /**
     * @return BelongsToMany<User, $this>
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, UserAnimeList::class)->withTimestamps();
    }

    /**
     * @param Builder<$this> $query
     * @return Builder<$this>
     */
    public function scopeReleased(Builder $query): Builder
    {
        return $query->where('status', StatusEnum::RELEASED);
    }

    /**
     * @param Builder<$this> $query
     * @return Builder<$this>
     */
    public function scopeUnreleased(Builder $query): Builder
    {
        return $query->whereNot('status', StatusEnum::RELEASED);
    }

    /**
     * @param Builder<$this> $query
     * @return Builder<$this>
     */
    public function scopeCountScrapedPerMonth(Builder $query): Builder
    {
        return $query->selectRaw('COUNT(id) as per_month, MONTH(created_at) as month_number')
                     ->groupBy('month_number');
    }
}
