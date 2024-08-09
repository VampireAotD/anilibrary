<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Anime;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
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
use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\ClientBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * @var array<class-string, class-string>
     */
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
     */
    public function register(): void
    {
        $this->setUpElasticsearchClient();
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->enforceMorphAliases();
        Model::shouldBeStrict(!$this->app->isProduction());
    }

    private function setUpElasticsearchClient(): void
    {
        $this->app->singleton(Client::class, function () {
            $rawHosts = config('elasticsearch.hosts');
            $hosts    = [];

            foreach ($rawHosts as $rawHost) {
                $hosts[] = sprintf(
                    '%s://%s:%d',
                    Arr::get($rawHost, 'scheme'),
                    Arr::get($rawHost, 'host'),
                    Arr::get($rawHost, 'port')
                );
            }

            return ClientBuilder::create()
                                ->setHosts($hosts)
                                ->setBasicAuthentication(
                                    config('elasticsearch.auth.username'),
                                    config('elasticsearch.auth.password')
                                )->build();
        });
    }

    private function enforceMorphAliases(): void
    {
        Relation::enforceMorphMap([
            'user'       => User::class,
            'role'       => Role::class,
            'anime'      => Anime::class,
            'permission' => Permission::class,
        ]);
    }
}
