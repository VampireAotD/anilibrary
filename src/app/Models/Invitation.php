<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Invitation\StatusEnum;
use App\Models\Concerns\Filterable;
use Database\Factories\InvitationFactory;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperInvitation
 */
class Invitation extends Model
{
    use HasUuids;
    /** @use HasFactory<InvitationFactory> */
    use HasFactory;
    use Filterable;

    protected $fillable = ['email', 'status', 'expires_at'];

    /**
     * @return array{status: 'App\Enums\Invitation\StatusEnum', 'expires_at': 'datetime'}
     */
    protected function casts(): array
    {
        return [
            'status'     => StatusEnum::class,
            'expires_at' => 'datetime',
        ];
    }

    /**
     * @param Builder<$this> $query
     * @return Builder<$this>
     */
    #[Scope]
    protected function pending(Builder $query): Builder
    {
        return $query->where('status', StatusEnum::PENDING);
    }

    /**
     * @param Builder<$this> $query
     * @return Builder<$this>
     */
    #[Scope]
    protected function accepted(Builder $query): Builder
    {
        return $query->where('status', StatusEnum::ACCEPTED);
    }

    /**
     * @param Builder<$this> $query
     * @return Builder<$this>
     */
    #[Scope]
    protected function declined(Builder $query): Builder
    {
        return $query->where('status', StatusEnum::DECLINED);
    }

    /**
     * @param Builder<$this> $query
     * @return Builder<$this>
     */
    #[Scope]
    protected function expired(Builder $query): Builder
    {
        return $query->where('status', StatusEnum::ACCEPTED)->where('expires_at', '<=', now());
    }
}
