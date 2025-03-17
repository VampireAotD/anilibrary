<?php

declare(strict_types=1);

namespace App\Models\Pivots;

use App\Enums\UserAnimeList\StatusEnum;
use App\Models\Anime;
use App\Models\User;
use Database\Factories\UserAnimeListFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @mixin IdeHelperUserAnimeList
 */
class UserAnimeList extends Pivot
{
    /** @use HasFactory<UserAnimeListFactory> */
    use HasFactory;

    public $incrementing = false;

    protected $fillable = ['user_id', 'anime_id', 'status', 'rating', 'episodes'];

    /**
     * @return array<string, class-string>
     */
    protected function casts(): array
    {
        return [
            'status' => StatusEnum::class,
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<Anime, $this>
     */
    public function anime(): BelongsTo
    {
        return $this->belongsTo(Anime::class);
    }
}
