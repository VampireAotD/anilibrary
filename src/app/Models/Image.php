<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * @mixin IdeHelperImage
 */
class Image extends Model
{
    use HasUuids;
    use HasFactory;

    protected $fillable = ['path', 'name', 'hash'];

    public function animes(): MorphToMany
    {
        return $this->morphedByMany(Anime::class, 'model', 'has_images');
    }

    /**
     * @psalm-suppress TooManyTemplateParams Suppressed because PHPStan needs description, but Psalm conflicts with it
     * @return Attribute<bool, never>
     */
    protected function isDefault(): Attribute
    {
        return Attribute::make(
            get: fn(): bool => $this->path === config('cloudinary.default_image'),
        )->shouldCache();
    }
}
