<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class Anime
 * @package App\Models
 */
class Anime extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
      'title',
      'url',
      'favourite_voice_acting',
    ];

    /**
     * @return HasOne
     */
    public function voiceActing(): HasOne
    {
        return $this->hasOne(VoiceActing::class);
    }
}
