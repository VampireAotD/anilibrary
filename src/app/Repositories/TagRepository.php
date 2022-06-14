<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Enums\TagSeederEnum;
use App\Models\Tag;
use App\Repositories\Contracts\Tag\TagRepositoryInterface;

/**
 * Class TagRepository
 * @package App\Repositories
 */
class TagRepository extends BaseRepository implements TagRepositoryInterface
{
    /**
     * @return string
     */
    protected function resolveModel(): string
    {
        return Tag::class;
    }

    /**
     * @param int $telegramId
     * @return array
     */
    public function findByTelegramId(int $telegramId): array
    {
        return match ($telegramId) {
            config('admin.id') => [
                $this->findByName(TagSeederEnum::ADMIN_TAG->value)?->id,
            ],
            default => []
        };
    }

    /**
     * @param string $name
     * @param array $columns
     * @return Tag|null
     */
    public function findByName(string $name, array $columns = ['*']): ?Tag
    {
        return $this->query()->select($columns)->where('name', $name)->first();
    }
}
