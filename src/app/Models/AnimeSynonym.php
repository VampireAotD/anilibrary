<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Traits\WithoutPrimaryKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\AnimeSynonym
 *
 * @property string                          $anime_id
 * @property string                          $synonym
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Anime          $anime
 * @method static \Database\Factories\AnimeSynonymFactory            factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|AnimeSynonym newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AnimeSynonym newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AnimeSynonym query()
 * @method static \Illuminate\Database\Eloquent\Builder|AnimeSynonym whereAnimeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnimeSynonym whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnimeSynonym whereSynonym($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnimeSynonym whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class AnimeSynonym extends Model
{
    use HasFactory;
    use WithoutPrimaryKey;

    protected $fillable = ['anime_id', 'synonym'];

    /**
     * @return BelongsTo
     */
    public function anime(): BelongsTo
    {
        return $this->belongsTo(Anime::class);
    }
}
