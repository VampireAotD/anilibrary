<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * App\Models\Image
 *
 * @property string                          $id
 * @property string                          $model_type
 * @property string                          $model_id
 * @property string                          $path
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Model|\Eloquent            $anime
 * @method static \Illuminate\Database\Eloquent\Builder|Image newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Image newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Image query()
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereModelType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Image wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string                          $alias
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereAlias($value)
 */
class Image extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'model_type',
        'model_id',
        'path',
        'alias',
    ];

    /**
     * @return MorphTo
     */
    public function anime(): MorphTo
    {
        return $this->morphTo(Anime::class);
    }
}
