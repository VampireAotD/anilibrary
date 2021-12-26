<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;

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
    ];

    /**
     * @return BelongsToMany
     */
    public function voiceActing(): BelongsToMany
    {
        return $this->belongsToMany(VoiceActing::class)->using(AnimeVoiceActing::class);
    }

    /**
     * @return MorphOne
     */
    public function image(): MorphOne
    {
        return $this->morphOne(Image::class, 'model');
    }
}
