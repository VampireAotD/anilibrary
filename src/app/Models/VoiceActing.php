<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\Filterable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperVoiceActing
 */
class VoiceActing extends Model
{
    use HasUuids;
    use HasFactory;
    use Filterable;

    protected $table = 'voice_acting';

    protected $fillable = ['name'];

    protected $hidden = ['pivot'];

    public function anime(): HasMany
    {
        return $this->hasMany(Anime::class);
    }
}
