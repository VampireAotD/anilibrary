<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations\Anime;

use App\Models\Anime;
use App\Repositories\Anime\AnimeRepositoryInterface;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Illuminate\Http\Response;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;
use Rebing\GraphQL\Support\SelectFields;

class UpdateAnimeMutation extends Mutation
{
    protected $attributes = [
        'name'        => 'UpdateAnime',
        'description' => 'Mutation to update anime',
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
            'input' => [
                'type' => GraphQL::type('UpdateAnimeInput'),
            ],
        ];
    }

    public function resolve($root, array $args, $context, ResolveInfo $resolveInfo, SelectFields $fields): Anime
    {
        /** @var Anime|null $anime */
        $anime = $this->animeRepository->findById($args['input']['id']);

        abort_if(!$anime, Response::HTTP_NOT_FOUND, 'Anime not found');

        return $anime->updateOrCreate(['id' => $args['input']['id']], $args['input']);
    }
}
