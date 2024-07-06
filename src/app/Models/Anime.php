<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Anime\StatusEnum;
use App\Enums\Anime\TypeEnum;
use App\Models\Concerns\Filterable;
use App\Models\Pivots\AnimeGenre;
use App\Models\Pivots\AnimeVoiceActing;
use App\Observers\AnimeObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

#[ObservedBy(AnimeObserver::class)]
/**
 * @mixin IdeHelperAnime
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
        'type',
        'year',
    ];

    /**
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

    public function image(): MorphOne
    {
        return $this->morphOne(Image::class, 'model')->withDefault([
            'path' => config('cloudinary.default_image'),
        ]);
    }

    public function urls(): HasMany
    {
        return $this->hasMany(AnimeUrl::class);
    }

    public function synonyms(): HasMany
    {
        return $this->hasMany(AnimeSynonym::class);
    }

    public function voiceActing(): BelongsToMany
    {
        return $this->belongsToMany(VoiceActing::class)->using(AnimeVoiceActing::class);
    }

    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class, AnimeGenre::getTableName())->using(AnimeGenre::class);
    }

    /**
     * @psalm-suppress TooManyTemplateParams
     * @return Attribute<string, never>
     */
    protected function toTelegramCaption(): Attribute
    {
        return Attribute::make(
            get: fn(): string => sprintf(
                "Название: %s\nСтатус: %s\nЭпизоды: %s\nОценка: %s\nОзвучки: %s\nЖанры: %s",
                $this->title,
                $this->status->value,
                $this->episodes,
                $this->rating,
                $this->voiceActing->implode('name', ', '),
                $this->genres->implode('name', ', '),
            )
        )->shouldCache();
    }
}
