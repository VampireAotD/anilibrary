<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Spatie\Permission\Models\Role as SpatieRole;

/**
 * Class Role
 * @package App\Models
 */
class Role extends SpatieRole
{
    use HasUuids;
}
