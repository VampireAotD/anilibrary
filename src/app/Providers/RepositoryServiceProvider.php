<?php

declare(strict_types=1);

namespace App\Providers;

use App\Repositories\AnimeRepository;
use App\Repositories\Contracts\AnimeRepositoryInterface;
use App\Repositories\Contracts\GenreRepositoryInterface;
use App\Repositories\Contracts\TelegramUserRepositoryInterface;
use App\Repositories\Contracts\VoiceActingRepositoryInterface;
use App\Repositories\GenreRepository;
use App\Repositories\TelegramUserRepository;
use App\Repositories\VoiceActingRepository;
use Illuminate\Support\ServiceProvider;

/**
 * Class RepositoryServiceProvider
 * @package App\Providers
 */
class RepositoryServiceProvider extends ServiceProvider
{
    public array $bindings = [
        TelegramUserRepositoryInterface::class => TelegramUserRepository::class,
        AnimeRepositoryInterface::class        => AnimeRepository::class,
        VoiceActingRepositoryInterface::class  => VoiceActingRepository::class,
        GenreRepositoryInterface::class        => GenreRepository::class,
    ];

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
        //
    }
}
