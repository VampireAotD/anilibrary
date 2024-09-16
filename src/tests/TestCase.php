<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Http;
use Override;

abstract class TestCase extends BaseTestCase
{
    /**
     * Indicates whether the default seeder should run before each test.
     */
    protected bool $seed = true;

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        Http::preventStrayRequests();

        $this->withoutVite();
    }
}
