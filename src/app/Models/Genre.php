<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\Filterable;
use App\Models\Pivots\AnimeGenre;
use Database\Factories\GenreFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @mixin IdeHelperGenre
 */
class Genre extends Model
{
    use HasUuids;
    /** @use HasFactory<GenreFactory> */
    use HasFactory;
    use Filterable;
    use SoftDeletes;

    protected $fillable = ['name'];

    protected $hidden = ['pivot'];

    public function anime(): BelongsToMany
    {
        return $this->belongsToMany(Anime::class)->using(AnimeGenre::class);
    }
}
