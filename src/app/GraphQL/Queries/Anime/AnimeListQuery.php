<?php

declare(strict_types=1);

namespace App\GraphQL\Queries\Anime;

use App\Repositories\Anime\AnimeRepositoryInterface;
use App\Repositories\Filters\PaginationFilter;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Illuminate\Pagination\LengthAwarePaginator;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;

class AnimeListQuery extends Query
{
    protected $attributes = [
        'name'        => 'AnimeList',
        'description' => 'A query to get all anime with their relations',
    ];

    public function __construct(protected AnimeRepositoryInterface $animeRepository)
    {
    }

    public function type(): Type
    {
        return GraphQL::paginate('Anime');
    }

    public function args(): array
    {
        return [
            'page'    => [
                'type'         => Type::int(),
                'description'  => 'Current page of anime list',
                'defaultValue' => 1,
            ],
            'perPage' => [
                'type'         => Type::int(),
                'description'  => 'Limit of how much anime can be in list',
                'defaultValue' => 20,
            ],
        ];
    }

    public function resolve(
        $root,
        array $args,
        $context,
        ResolveInfo $resolveInfo,
        SelectFields $fields
    ): LengthAwarePaginator {
        $select = $fields->getSelect();
        $with   = $fields->getRelations();

        $filter = new PaginationFilter($args['page'], $args['perPage'], $select, $with);

        return $this->animeRepository->paginate($filter);
    }
}
