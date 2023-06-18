<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Enums\TagEnum;
use App\Models\Tag;
use App\Repositories\Contracts\TagRepositoryInterface;
use App\Repositories\Traits\CanSearchByName;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class TagRepository
 * @package App\Repositories
 */
class TagRepository extends BaseRepository implements TagRepositoryInterface
{
    use CanSearchByName;

    /**
     * @return Builder|Tag
     */
    protected function model(): Builder | Tag
    {
        return Tag::query();
    }

    /**
     * @param int $telegramId
     * @return array<string>|array
     */
    public function findByTelegramId(int $telegramId): array
    {
        return match ($telegramId) {
            config('admin.id') => [
                /** @phpstan-ignore-next-line */
                $this->findByName(TagEnum::ADMIN_TAG->value)?->id,
            ],
            default            => []
        };
    }
}
