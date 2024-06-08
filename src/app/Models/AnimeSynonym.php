<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperAnimeSynonym
 */
class AnimeSynonym extends Model
{
    use HasFactory;

    protected $fillable = ['anime_id', 'name'];

    public function anime(): BelongsTo
    {
        return $this->belongsTo(Anime::class);
    }
}
