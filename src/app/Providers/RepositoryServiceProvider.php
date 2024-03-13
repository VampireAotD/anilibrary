<?php

declare(strict_types=1);

namespace App\Providers;

use App\Repositories\Anime\AnimeRepository;
use App\Repositories\Anime\AnimeRepositoryInterface;
use App\Repositories\AnimeUrl\AnimeUrlRepository;
use App\Repositories\AnimeUrl\AnimeUrlRepositoryInterface;
use App\Repositories\Genre\GenreRepository;
use App\Repositories\Genre\GenreRepositoryInterface;
use App\Repositories\TelegramUser\TelegramUserRepository;
use App\Repositories\TelegramUser\TelegramUserRepositoryInterface;
use App\Repositories\User\UserRepository;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\VoiceActing\VoiceActingRepository;
use App\Repositories\VoiceActing\VoiceActingRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public array $bindings = [
        TelegramUserRepositoryInterface::class => TelegramUserRepository::class,
        AnimeRepositoryInterface::class        => AnimeRepository::class,
        VoiceActingRepositoryInterface::class  => VoiceActingRepository::class,
        GenreRepositoryInterface::class        => GenreRepository::class,
        UserRepositoryInterface::class         => UserRepository::class,
        AnimeUrlRepositoryInterface::class     => AnimeUrlRepository::class,
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
