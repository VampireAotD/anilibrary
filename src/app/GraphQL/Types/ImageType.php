<?php

declare(strict_types=1);

namespace App\GraphQL\Types;

use App\Models\Image;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class ImageType extends GraphQLType
{
    protected $attributes = [
        'name'        => 'Image',
        'description' => 'Representation of image',
        'model'       => Image::class,
    ];

    public function fields(): array
    {
        return [
            'url' => [
                'type'        => Type::nonNull(Type::string()),
                'description' => 'URL where image is stored',
                'alias'       => 'path',
            ],
        ];
    }
}
