<?php

declare(strict_types=1);

namespace App\GraphQL\Types\Anime;

use App\Models\AnimeSynonym;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class AnimeSynonymType extends GraphQLType
{
    protected $attributes = [
        'name'        => 'AnimeSynonym',
        'description' => 'Representation of similar name for anime',
        'model'       => AnimeSynonym::class,
    ];

    public function fields(): array
    {
        return [
            'anime_id' => [
                'type'        => Type::nonNull(Type::id()),
                'description' => 'Anime ID url is related to',
            ],
            'synonym'  => [
                'type'        => Type::nonNull(Type::string()),
                'description' => 'Synonym',
            ],
        ];
    }
}
