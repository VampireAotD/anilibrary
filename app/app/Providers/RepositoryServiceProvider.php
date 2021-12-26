<?php

namespace App\Providers;

use App\Repositories\AnimeRepository;
use App\Repositories\Contracts\Anime\Repository as AnimeRepositoryInterface;
use App\Repositories\Contracts\TelegramUser\Repository as TelegramUserRepositoryInterface;
use App\Repositories\Contracts\VoiceActing\Repository as VoiceActingRepositoryInterface;
use App\Repositories\TelegramUserRepository;
use App\Repositories\VoiceActingRepository;
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
        $this->app->bind(TelegramUserRepositoryInterface::class, TelegramUserRepository::class);
        $this->app->bind(VoiceActingRepositoryInterface::class, VoiceActingRepository::class);
        $this->app->bind(AnimeRepositoryInterface::class, AnimeRepository::class);
    }
}
