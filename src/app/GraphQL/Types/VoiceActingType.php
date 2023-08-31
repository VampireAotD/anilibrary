<?php

declare(strict_types=1);

namespace App\GraphQL\Types;

use App\Models\VoiceActing;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class VoiceActingType extends GraphQLType
{
    protected $attributes = [
        'name'        => 'VoiceActing',
        'description' => 'Representation of voice acting',
        'model'       => VoiceActing::class,
    ];

    public function fields(): array
    {
        return [
            'name' => [
                'type'        => Type::nonNull(Type::string()),
                'description' => 'Name of voice acting',
            ],
        ];
    }
}
