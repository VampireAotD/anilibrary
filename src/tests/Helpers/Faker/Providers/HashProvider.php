<?php

declare(strict_types=1);

namespace Tests\Helpers\Faker\Providers;

use Faker\Provider\Base;

class HashProvider extends Base
{
    public function sha512(?string $content = null): string
    {
        return hash('sha512', $content ?? $this->generator->sentence);
    }
}
