<?php

declare(strict_types=1);

namespace App\Database\Relations;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Concerns\SupportsDefaultModels;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Arr;
use Override;

/**
 * @template TRelatedModel of Model
 * @template TDeclaringModel of Model
 *
 * @extends MorphToMany<TRelatedModel, TDeclaringModel>
 */
final class OneOfMorphToMany extends MorphToMany
{
    use SupportsDefaultModels;

    /**
     * Match the eagerly loaded results to their parents.
     * This method is used when `with` or `load` methods are called.
     */
    #[Override]
    public function match(array $models, Collection $results, $relation): array
    {
        $dictionary = $this->buildDictionary($results);

        foreach ($models as $model) {
            $key = $this->getDictionaryKey($model->{$this->parentKey});

            // If there are related entries to model, we need to take only first one and set it
            if (isset($dictionary[$key])) {
                $model->setRelation($relation, Arr::first($dictionary[$key]));
                continue;
            }

            // If there were no related entries - set default one
            $model->setRelation($relation, $this->getDefaultFor($model));
        }

        return $models;
    }

    /**
     * Get results of the relationship. In the case of `OneOfMorphToMany` it will only get one result.
     * @phpstan-ignore-next-line Suppressed because of overriding
     */
    #[Override]
    public function getResults(): ?Model
    {
        return $this->query->first() ?: $this->getDefaultFor($this->parent);
    }

    /**
     * Make a new related instance for the given model.
     */
    #[Override]
    protected function newRelatedInstanceFor(Model $parent): Model
    {
        return $this->related->newInstance()
                             ->setAttribute($this->getForeignPivotKeyName(), $parent->getKey())
                             ->setAttribute($this->getMorphType(), $this->morphClass);
    }
}
