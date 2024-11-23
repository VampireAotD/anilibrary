<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\TelegramUserFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @mixin IdeHelperTelegramUser
 */
class TelegramUser extends Model
{
    use HasUuids;
    /** @use HasFactory<TelegramUserFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'telegram_id',
        'first_name',
        'last_name',
        'username',
    ];

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
