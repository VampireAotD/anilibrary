<?php

declare(strict_types=1);

namespace Tests;

use Faker\Factory;
use Faker\Generator;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;
use Tests\Helpers\Faker\Providers\AnimeInformationProvider;

trait CreatesApplication
{
    /**
     * Creates the application.
     *
     * @return Application
     */
    public function createApplication(): Application
    {
        /** @var $app Application */
        $app = require __DIR__ . '/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        $app->singleton(Generator::class, function () {
            $faker = Factory::create();

            $faker->addProvider(new AnimeInformationProvider($faker));

            return $faker;
        });

        $app->bind(Generator::class . ':' . config('app.faker_locale'), Generator::class);

        return $app;
    }
}
