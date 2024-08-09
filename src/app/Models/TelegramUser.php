<?php

declare(strict_types=1);

namespace App\Models;

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
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'telegram_id',
        'first_name',
        'last_name',
        'username',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
