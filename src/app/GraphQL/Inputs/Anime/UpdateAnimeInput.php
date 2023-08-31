<?php

declare(strict_types=1);

namespace App\GraphQL\Inputs\Anime;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\InputType;

class UpdateAnimeInput extends InputType
{
    protected $attributes = [
        'name'        => 'UpdateAnimeInput',
        'description' => 'Update anime input',
    ];

    public function fields(): array
    {
        return [
            'id'    => [
                'type'        => Type::nonNull(Type::id()),
                'description' => 'Anime ID',
                'rules'       => ['required', 'uuid', 'exists:animes,id'],
            ],
            'title' => [
                'type'  => Type::string(),
                'rules' => ['required', 'string'],
            ],
        ];
    }
}
