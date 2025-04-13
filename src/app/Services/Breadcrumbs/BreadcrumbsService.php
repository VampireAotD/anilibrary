<?php

declare(strict_types=1);

namespace App\Services\Breadcrumbs;

use Diglactic\Breadcrumbs\Exceptions\InvalidBreadcrumbException;
use Diglactic\Breadcrumbs\Exceptions\UnnamedRouteException;
use Diglactic\Breadcrumbs\Manager;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Collection;
use stdClass;

final readonly class BreadcrumbsService
{
    public function __construct(private Manager $manager)
    {
    }

    /**
     * @return list<array{url: string, title: string, is_current_page: bool}>
     */
    public function generate(Request $request): array
    {
        $breadcrumbs = $this->getBreadcrumbs($request->route());

        return $breadcrumbs->map(static fn(stdClass $breadcrumb) => [
            'url'             => $breadcrumb->url,
            'title'           => $breadcrumb->title,
            'is_current_page' => $request->fullUrlIs($breadcrumb->url),
        ])->toArray();
    }

    private function getBreadcrumbs(Route $route): Collection
    {
        try {
            return $this->manager->generate($route->getName(), ...array_values($route->parameters()));
        } catch (InvalidBreadcrumbException | UnnamedRouteException) {
            return new Collection();
        }
    }
}
