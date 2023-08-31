<?php

declare(strict_types=1);

namespace App\GraphQL\Types;

use App\Models\Anime;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class AnimeType extends GraphQLType
{
    protected $attributes = [
        'name'  => 'Anime',
        'type'  => 'Anime type',
        'model' => Anime::class,
    ];

    public function fields(): array
    {
        return [
            'id'          => [
                'type'        => Type::nonNull(Type::id()),
                'description' => 'Anime ID',
            ],
            'image'       => [
                'type'        => GraphQL::type('Image'),
                'description' => 'Anime image',
            ],
            'title'       => [
                'type'        => Type::nonNull(Type::string()),
                'description' => 'Main name of anime',
            ],
            'status'      => [
                'type'        => Type::nonNull(Type::string()),
                'description' => 'Anime status',
            ],
            'rating'      => [
                'type'        => Type::nonNull(Type::float()),
                'description' => 'Anime rating',
            ],
            'episodes'    => [
                'type'        => Type::nonNull(Type::string()),
                'description' => 'Anime episodes',
            ],
            'urls'        => [
                'type'        => Type::nonNull(Type::listOf(GraphQL::type('AnimeUrl'))),
                'description' => 'List of anime urls',
            ],
            'synonyms'    => [
                'type'        => Type::nonNull(Type::listOf(GraphQL::type('AnimeSynonym'))),
                'description' => 'List of anime synonyms',
            ],
            'voiceActing' => [
                'type'        => Type::listOf(GraphQL::type('VoiceActing')),
                'description' => 'List of available voice acting',
            ],
            'genres'      => [
                'type'        => Type::listOf(GraphQL::type('Genre')),
                'description' => 'List of available genres',
            ],
        ];
    }
}
