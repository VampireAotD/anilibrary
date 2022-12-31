<?php

declare(strict_types=1);

namespace App\Services;

/**
 * Class AnimeSynonymService
 * @package App\Services
 */
class AnimeSynonymService
{
    /**
     * @param array<string> $plainSynonyms
     * @return array<array<string, string>>
     */
    public function mapIntoSynonymsArray(array $plainSynonyms): array
    {
        return array_map(fn(string $synonym) => ['synonym' => $synonym], $plainSynonyms);
    }
}
