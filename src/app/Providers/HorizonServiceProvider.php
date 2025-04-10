<?php

declare(strict_types=1);

namespace App\Providers;

use App\Enums\RoleEnum;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Laravel\Horizon\HorizonApplicationServiceProvider;

class HorizonServiceProvider extends HorizonApplicationServiceProvider
{
    /**
     * Register services.
     */
    #[\Override]
    public function boot(): void
    {
        parent::boot();
    }

    /**
     * Register the Horizon gate.
     *
     * This gate determines who can access Horizon in non-local environments.
     */
    #[\Override]
    protected function gate(): void
    {
        Gate::define('viewHorizon', fn(User $user) => $user->hasRole(RoleEnum::OWNER));
    }
}
