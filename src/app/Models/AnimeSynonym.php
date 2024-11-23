<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\AnimeUrlFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperAnimeSynonym
 */
class AnimeSynonym extends Model
{
    use HasUuids;
    /** @use HasFactory<AnimeUrlFactory> */
    use HasFactory;

    protected $fillable = ['anime_id', 'name'];

    /**
     * @return BelongsTo<Anime, $this>
     */
    public function anime(): BelongsTo
    {
        return $this->belongsTo(Anime::class);
    }
}
