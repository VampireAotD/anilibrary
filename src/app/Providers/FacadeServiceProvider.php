<?php

declare(strict_types=1);

namespace App\Providers;

use App\Facades\Telegram\History\UserHistory as UserHistoryFacade;
use App\Telegram\History\UserHistory;
use Illuminate\Support\ServiceProvider;

class FacadeServiceProvider extends ServiceProvider
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
        $this->app->bind(UserHistoryFacade::getFacadeAccessor(), fn() => $this->app->make(UserHistory::class));
    }
}
