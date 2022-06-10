<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\VoiceActing
 *
 * @property string                                                            $id
 * @property string                                                            $name
 * @property \Illuminate\Support\Carbon|null                                   $created_at
 * @property \Illuminate\Support\Carbon|null                                   $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Anime[] $anime
 * @property-read int|null                                                     $anime_count
 * @method static \Illuminate\Database\Eloquent\Builder|VoiceActing newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VoiceActing newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VoiceActing query()
 * @method static \Illuminate\Database\Eloquent\Builder|VoiceActing whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VoiceActing whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VoiceActing whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VoiceActing whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class VoiceActing extends Model
{
    use HasFactory, HasUuid;

    protected $table = 'voice_acting';

    protected $fillable = ['name'];

    protected $hidden = ['pivot'];

    /**
     * @return HasMany
     */
    public function anime(): HasMany
    {
        return $this->hasMany(Anime::class);
    }
}
