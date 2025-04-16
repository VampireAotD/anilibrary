<?php

declare(strict_types=1);

use Rector\Caching\ValueObject\Storage\FileCacheStorage;
use Rector\CodeQuality\Rector\FuncCall\CompactToVariablesRector;
use Rector\CodingStyle\Rector\ClassMethod\MakeInheritedMethodVisibilitySameAsParentRector;
use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\Property\RemoveUnusedPrivatePropertyRector;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;
use Rector\ValueObject\PhpVersion;
use RectorLaravel\Rector\PropertyFetch\ReplaceFakerInstanceWithHelperRector;
use RectorLaravel\Set\LaravelLevelSetList;

return RectorConfig::configure()
                   ->withPaths([
                       __DIR__ . '/app',
                       __DIR__ . '/config',
                       __DIR__ . '/database',
                       __DIR__ . '/resources',
                       __DIR__ . '/routes',
                       __DIR__ . '/tests',
                   ])
                   ->withCache(cacheDirectory: __DIR__ . '/storage/rector', cacheClass: FileCacheStorage::class)
                   ->withPhpVersion(PhpVersion::PHP_83)
                   ->withRules([
                       RemoveUnusedPrivatePropertyRector::class,
                   ])
                   ->withSets([
                       LevelSetList::UP_TO_PHP_84,
                       LaravelLevelSetList::UP_TO_LARAVEL_120,
                       SetList::CODE_QUALITY,
                       SetList::CODING_STYLE,
                       SetList::EARLY_RETURN,
                       SetList::STRICT_BOOLEANS,
                   ])
                   ->withSkip([
                       CompactToVariablesRector::class,
                       ReplaceFakerInstanceWithHelperRector::class,
                       MakeInheritedMethodVisibilitySameAsParentRector::class,

                       '*/Models/Concerns/HasOneOfMorphToManyRelation.php',
                   ]);
