<?php

declare(strict_types=1);

namespace App\Providers;

use App\Facades\Telegram\State\UserStateFacade;
use App\Telegram\State\UserState;
use Illuminate\Support\ServiceProvider;

class FacadeServiceProvider extends ServiceProvider
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
        $this->app->bind(UserStateFacade::getFacadeAccessor(), fn() => $this->app->make(UserState::class));
    }
}
