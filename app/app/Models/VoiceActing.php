<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VoiceActing extends Model
{
    use HasFactory, HasUuid;

    protected $table = 'voice_acting';

    protected $fillable = [
        'name',
    ];

    /**
     * @return HasMany
     */
    public function anime(): HasMany
    {
        return $this->hasMany(Anime::class);
    }
}
