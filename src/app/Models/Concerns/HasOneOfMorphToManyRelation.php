<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Database\Relations\OneOfMorphToMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @mixin Model
 * @infection-ignore-all
 * @codeCoverageIgnore
 */
trait HasOneOfMorphToManyRelation
{
    /**
     * Define a polymorphic many-to-many relationship that return only one result.
     * Modified version of `morphToMany` method.
     *
     * @template TRelatedModel of Model
     *
     * @param class-string<TRelatedModel> $related
     * @param string                      $name
     * @param string|null                 $table
     * @param string|null                 $foreignPivotKey
     * @param string|null                 $relatedPivotKey
     * @param string|null                 $parentKey
     * @param string|null                 $relatedKey
     * @param string|null                 $relation
     * @param bool                        $inverse
     * @return OneOfMorphToMany<TRelatedModel, $this>
     */
    public function oneOfMorphToMany(
        $related,
        string $name,
        ?string $table = null,
        ?string $foreignPivotKey = null,
        ?string $relatedPivotKey = null,
        ?string $parentKey = null,
        ?string $relatedKey = null,
        ?string $relation = null,
        bool $inverse = false
    ): OneOfMorphToMany {
        $relation = $relation ?: $this->guessBelongsToManyRelation();

        $instance = $this->newRelatedInstance($related);

        $foreignPivotKey = $foreignPivotKey ?: $name . '_id';

        $relatedPivotKey = $relatedPivotKey ?: $instance->getForeignKey();

        if (!$table) {
            $words = preg_split('/(_)/u', $name, -1, PREG_SPLIT_DELIM_CAPTURE);

            $lastWord = array_pop($words);

            $table = implode('', $words) . Str::plural($lastWord);
        }

        // @phpstan-ignore return.type (Suppressed because of overriding)
        return new OneOfMorphToMany(
            query          : $instance->newQuery(),
            parent         : $this,
            name           : $name,
            table          : $table,
            foreignPivotKey: $foreignPivotKey,
            relatedPivotKey: $relatedPivotKey,
            parentKey      : $parentKey ?: $this->getKeyName(),
            relatedKey     : $relatedKey ?: $instance->getKeyName(),
            relationName   : $relation,
            inverse        : $inverse
        );
    }
}
