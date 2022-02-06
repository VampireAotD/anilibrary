<?php

namespace App\Providers;

use App\Repositories\AnimeRepository;
use App\Repositories\Contracts\Anime\AnimeRepositoryInterface;
use App\Repositories\Contracts\Tag\TagRepositoryInterface;
use App\Repositories\Contracts\TelegramUser\TelegramUserRepositoryInterface;
use App\Repositories\TagRepository;
use App\Repositories\TelegramUserRepository;
use Illuminate\Support\ServiceProvider;

/**
 * Class RepositoryServiceProvider
 * @package App\Providers
 */
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
