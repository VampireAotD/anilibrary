<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Anime;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\ClientBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
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
        Vite::prefetch(concurrency: 3);
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
