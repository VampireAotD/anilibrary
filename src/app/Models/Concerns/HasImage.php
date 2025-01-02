<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Database\Relations\OneOfMorphToMany;
use App\Models\Image;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Model
 * @property-read Image $image
 * @infection-ignore-all
 * @codeCoverageIgnore
 */
trait HasImage
{
    use HasOneOfMorphToManyRelation;

    /**
     * @return OneOfMorphToMany<Image, $this>
     */
    public function image(): OneOfMorphToMany
    {
        return $this->oneOfMorphToMany(Image::class, 'model', 'has_images')->withTimestamps()->withDefault([
            'path' => config('cloudinary.default_image'),
        ]);
    }

    public function attachImage(Image $image): void
    {
        $this->detachImage();
        $this->image()->attach($image);
    }

    public function detachImage(): void
    {
        if ($this->image->is_default) {
            return;
        }

        $this->image()->detach();
    }
}
