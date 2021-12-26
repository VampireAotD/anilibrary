<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Image extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'model_type',
        'model_id',
        'path',
    ];

    /**
     * @return MorphTo
     */
    public function anime(): MorphTo
    {
        return $this->morphTo(Anime::class);
    }
}
