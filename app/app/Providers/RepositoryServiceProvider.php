<?php

namespace App\Providers;

use App\Repositories\Contracts\TelegramUser\Repository;
use App\Repositories\TelegramUserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(Repository::class, TelegramUserRepository::class);
    }
}
