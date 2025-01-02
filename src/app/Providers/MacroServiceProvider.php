<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\ServiceProvider;

class MacroServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        /**
         * @see https://github.com/laravel/framework/pull/40057
         */
        HasMany::macro(
            'upsertRelated',
            function (array $values, array | string $uniqueBy, array | null $update = null): int {
                data_set($values, '*.' . $this->getForeignKeyName(), $this->getParentKey());
                return $this->getRelated()->upsert($values, $uniqueBy, $update);
            }
        );
    }
}
