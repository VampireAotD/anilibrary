<?php

declare(strict_types=1);

namespace App\Providers;

use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\ClientBuilder;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->setUpElasticsearchClient();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        //
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
}
