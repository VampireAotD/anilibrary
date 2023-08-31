<?php

declare(strict_types=1);

namespace App\GraphQL\Queries\Anime;

use App\Repositories\Anime\AnimeRepositoryInterface;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Illuminate\Database\Eloquent\Model;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;

class AnimeQuery extends Query
{
    protected $attributes = [
        'name'        => 'Anime',
        'description' => 'A query to find single anime with all relations',
    ];

    public function __construct(protected AnimeRepositoryInterface $animeRepository)
    {
    }

    public function type(): Type
    {
        return GraphQL::type('Anime');
    }

    public function args(): array
    {
        return [
            'id' => [
                'type'        => Type::nonNull(Type::id()),
                'description' => 'Get anime by ID',
            ],
        ];
    }

    public function resolve($root, array $args, $context, ResolveInfo $resolveInfo, SelectFields $fields): ?Model
    {
        $with = $fields->getRelations();

        return $this->animeRepository->findById($args['id'])?->load($with);
    }
}
