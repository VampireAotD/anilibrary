<?php

declare(strict_types=1);

namespace Tests;

use Faker\Factory;
use Faker\Generator;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Override;
use Tests\Helpers\Faker\Providers\AnimeInformationProvider;

abstract class TestCase extends BaseTestCase
{
    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    #[Override]
    public function createApplication(): Application
    {
        $app = require Application::inferBasePath() . '/bootstrap/app.php';

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
