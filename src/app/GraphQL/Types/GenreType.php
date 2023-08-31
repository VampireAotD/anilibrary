<?php

declare(strict_types=1);

namespace App\GraphQL\Types;

use App\Models\Genre;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class GenreType extends GraphQLType
{
    protected $attributes = [
        'name'        => 'Genre',
        'description' => 'Representation of genre',
        'model'       => Genre::class,
    ];

    public function fields(): array
    {
        return [
            'name' => [
                'type'        => Type::nonNull(Type::string()),
                'description' => 'Name of genre',
            ],
        ];
    }
}
