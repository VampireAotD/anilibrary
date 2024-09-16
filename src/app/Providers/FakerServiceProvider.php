<?php

declare(strict_types=1);

namespace App\Providers;

use Faker\Factory;
use Faker\Generator;
use Illuminate\Support\ServiceProvider;
use Tests\Helpers\Faker\Providers\AnimeInformationProvider;
use Tests\Helpers\Faker\Providers\HashProvider;

class FakerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        if ($this->app->environment('testing')) {
            $this->app->singleton(Generator::class, function () {
                $faker = Factory::create();

                $faker->addProvider(new AnimeInformationProvider($faker));
                $faker->addProvider(new HashProvider($faker));

                return $faker;
            });

            $this->app->bind(Generator::class . ':' . config('app.faker_locale'), Generator::class);
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
