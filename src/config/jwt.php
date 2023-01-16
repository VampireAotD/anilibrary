<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | JWT Secret
    |--------------------------------------------------------------------------
    |
    | Secret to create JWT token for communication with different Anilibrary services
    |
    */

    'secret' => env('JWT_SECRET', ''),
];
