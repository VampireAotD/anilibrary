<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperAnimeUrl
 */
class AnimeUrl extends Model
{
    use HasFactory;

    protected $fillable = ['anime_id', 'url'];

    public function anime(): BelongsTo
    {
        return $this->belongsTo(Anime::class);
    }

    /**
     * @psalm-suppress TooManyTemplateParams
     * @return Attribute<string, never>
     */
    protected function domain(): Attribute
    {
        return Attribute::make(
            get: fn(): string => parse_url($this->url)['host'] ?? '',
        )->shouldCache();
    }
}
