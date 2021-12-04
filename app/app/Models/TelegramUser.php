<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class TelegramUser
 * @package App\Models
 */
class TelegramUser extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'telegram_id',
        'nickname',
        'username',
    ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
