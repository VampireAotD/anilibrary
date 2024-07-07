<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @mixin IdeHelperImage
 */
class Image extends Model
{
    use HasUuids;
    use HasFactory;

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

    /**
     * @psalm-suppress TooManyTemplateParams Suppressed because PHPStan needs description, but Psalm conflicts with it
     * @return Attribute<bool, never>
     */
    protected function default(): Attribute
    {
        return Attribute::make(
            get: fn(): bool => $this->path === config('cloudinary.default_image'),
        )->shouldCache();
    }
}
