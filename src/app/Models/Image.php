<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @mixin IdeHelperImage
 */
class Image extends Model
{
    use HasFactory;
    use HasUuids;

    protected $fillable = [
        'model_type',
        'model_id',
        'path',
        'alias',
    ];

    public function anime(): MorphTo
    {
        return $this->morphTo(Anime::class);
    }
}
