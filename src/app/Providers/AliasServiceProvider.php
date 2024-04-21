<?php

declare(strict_types=1);

namespace App\Providers;

use App\Facades\Telegram\State\UserStateFacade;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class AliasServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        AliasLoader::getInstance([
            'UserState' => UserStateFacade::class,
        ]);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
