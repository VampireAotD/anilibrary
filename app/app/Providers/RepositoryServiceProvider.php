<?php

namespace App\Providers;

use App\Repositories\AnimeRepository;
use App\Repositories\Contracts\Anime\Repository as AnimeRepositoryInterface;
use App\Repositories\Contracts\Tag\Repository as TagRepositoryInterface;
use App\Repositories\Contracts\TelegramUser\Repository as TelegramUserRepositoryInterface;
use App\Repositories\TagRepository;
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
        $this->app->bind(TelegramUserRepositoryInterface::class, TelegramUserRepository::class);
        $this->app->bind(AnimeRepositoryInterface::class, AnimeRepository::class);
        $this->app->bind(TagRepositoryInterface::class, TagRepository::class);
    }
}
