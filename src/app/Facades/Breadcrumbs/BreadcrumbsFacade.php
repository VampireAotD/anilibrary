<?php

declare(strict_types=1);

namespace App\Facades\Breadcrumbs;

use App\Services\Breadcrumbs\BreadcrumbsService;
use Illuminate\Support\Facades\Facade;

/**
 * @mixin BreadcrumbsService
 */
final class BreadcrumbsFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return BreadcrumbsService::class;
    }
}
