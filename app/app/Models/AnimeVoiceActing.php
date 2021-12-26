<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Relations\Pivot;

class AnimeVoiceActing extends Pivot
{
    use HasUuid;
}
