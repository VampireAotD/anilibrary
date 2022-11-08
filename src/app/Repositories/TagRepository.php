<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Enums\TagEnum;
use App\Models\Tag;
use App\Repositories\Contracts\TagRepositoryInterface;
use App\Repositories\Traits\CanSearchByName;

/**
 * Class TagRepository
 * @package App\Repositories
 */
class TagRepository extends BaseRepository implements TagRepositoryInterface
{
    use CanSearchByName;

    /**
     * @return string
     */
    protected function resolveModel(): string
    {
        return Tag::class;
    }

    /**
     * @param int $telegramId
     * @return string[]
     */
    public function findByTelegramId(int $telegramId): array
    {
        return match ($telegramId) {
            config('admin.id') => [
                $this->findByName(TagEnum::ADMIN_TAG->value)?->id,
            ],
            default            => []
        };
    }
}
