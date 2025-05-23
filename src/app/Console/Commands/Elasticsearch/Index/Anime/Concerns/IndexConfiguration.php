<?php

declare(strict_types=1);

namespace App\Console\Commands\Elasticsearch\Index\Anime\Concerns;

trait IndexConfiguration
{
    /**
     * @return array<string, array<string, mixed>>
     */
    protected function getIndexSettings(): array
    {
        return [
            'index' => [
                'max_ngram_diff' => 10,
            ],
            'analysis' => [
                'filter' => [
                    'english_stemmer' => [
                        'type'     => 'stemmer',
                        'language' => 'english',
                    ],
                    'russian_stemmer' => [
                        'type'     => 'stemmer',
                        'language' => 'russian',
                    ],
                    'english_stop' => [
                        'type'     => 'stop',
                        'language' => '_english_',
                    ],
                    'russian_stop' => [
                        'type'     => 'stop',
                        'language' => '_russian_',
                    ],
                    'autocomplete' => [
                        'type'     => 'ngram',
                        'min_gram' => 3,
                        'max_gram' => 8,
                    ],
                ],
                'analyzer' => [
                    'anime_analyzer' => [
                        'type'        => 'custom',
                        'char_filter' => [
                            'html_strip',
                        ],
                        'tokenizer' => 'standard',
                        'filter'    => [
                            'lowercase',
                            'english_stemmer',
                            'english_stop',
                            'russian_stemmer',
                            'russian_stop',
                            'autocomplete',
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    protected function getIndexMappings(): array
    {
        return [
            'properties' => [
                'title' => [
                    'type'     => 'text',
                    'analyzer' => 'anime_analyzer',
                ],
                'type' => [
                    'type' => 'keyword',
                ],
                'status' => [
                    'type' => 'keyword',
                ],
                'rating' => [
                    'type' => 'float',
                ],
                'episodes' => [
                    'type' => 'short',
                ],
                'year' => [
                    'type' => 'short',
                ],
                'urls.url' => [
                    'type' => 'keyword',
                ],
                'synonyms.synonym' => [
                    'type'     => 'text',
                    'analyzer' => 'anime_analyzer',
                ],
                'genres.name' => [
                    'type' => 'keyword',
                ],
                'voice_acting.name' => [
                    'type' => 'keyword',
                ],
            ],
        ];
    }
}
