<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Spatie\Permission\Models\Permission as SpatiePermission;

/**
 * Class Permission
 * @package App\Models
 */
class Permission extends SpatiePermission
{
    use HasUuids;
}
