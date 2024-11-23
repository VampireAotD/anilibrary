<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\Filterable;
use App\Models\Pivots\AnimeVoiceActing;
use Database\Factories\VoiceActingFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @mixin IdeHelperVoiceActing
 */
class VoiceActing extends Model
{
    use HasUuids;
    /** @use HasFactory<VoiceActingFactory> */
    use HasFactory;
    use Filterable;
    use SoftDeletes;

    protected $table = 'voice_acting';

    protected $fillable = ['name'];

    protected $hidden = ['pivot'];

    /**
     * @return BelongsToMany<Anime, $this>
     */
    public function anime(): BelongsToMany
    {
        return $this->belongsToMany(Anime::class)->using(AnimeVoiceActing::class);
    }
}
