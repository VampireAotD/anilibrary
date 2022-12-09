<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\ServiceProvider;

class MacroServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        HasMany::macro(
            'upsertRelated',
            function (array $values, array | string $uniqueBy, array | null $update = null): int {
                /** @var $this HasMany */
                data_set($values, '*.' . $this->getForeignKeyName(), $this->getParentKey());
                return $this->getRelated()->upsert($values, $uniqueBy, $update);
            }
        );
    }
}
