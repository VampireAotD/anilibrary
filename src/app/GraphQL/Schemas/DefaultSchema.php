<?php

declare(strict_types=1);

namespace App\GraphQL\Schemas;

use App\GraphQL\Inputs\Anime\UpdateAnimeInput;
use App\GraphQL\Mutations\Anime\UpdateAnimeMutation;
use App\GraphQL\Queries\Anime\AnimeListQuery;
use App\GraphQL\Queries\Anime\AnimeQuery;
use App\GraphQL\Types\Anime\AnimeSynonymType;
use App\GraphQL\Types\Anime\AnimeUrlType;
use App\GraphQL\Types\AnimeType;
use App\GraphQL\Types\GenreType;
use App\GraphQL\Types\ImageType;
use App\GraphQL\Types\VoiceActingType;
use Illuminate\Http\Request;
use Rebing\GraphQL\Support\Contracts\ConfigConvertible;
use Rebing\GraphQL\Support\ExecutionMiddleware\UnusedVariablesMiddleware;

class DefaultSchema implements ConfigConvertible
{
    public function toConfig(): array
    {
        return [
            'query'                => [
                AnimeQuery::class,
                AnimeListQuery::class,
            ],
            'mutation'             => [
                UpdateAnimeMutation::class,
            ],
            'types'                => [
                AnimeType::class,
                AnimeUrlType::class,
                AnimeSynonymType::class,
                ImageType::class,
                VoiceActingType::class,
                GenreType::class,
                UpdateAnimeInput::class,
            ],
            'middleware'           => ['auth'],
            'method'               => [Request::METHOD_GET, Request::METHOD_POST],
            'execution_middleware' => [UnusedVariablesMiddleware::class],
        ];
    }
}
