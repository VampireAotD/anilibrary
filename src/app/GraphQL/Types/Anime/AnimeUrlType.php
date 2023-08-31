<?php

declare(strict_types=1);

namespace App\GraphQL\Types\Anime;

use App\Models\AnimeUrl;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class AnimeUrlType extends GraphQLType
{
    protected $attributes = [
        'name'        => 'AnimeUrl',
        'description' => 'Representation of url for anime',
        'model'       => AnimeUrl::class,
    ];

    public function fields(): array
    {
        return [
            'anime_id' => [
                'type'        => Type::nonNull(Type::id()),
                'description' => 'Anime ID url is related to',
            ],
            'url'      => [
                'type'        => Type::nonNull(Type::string()),
                'description' => 'URL',
            ],
        ];
    }
}
