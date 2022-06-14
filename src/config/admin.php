<?php

return [
    /*-------------------------------------------------------------------------
    | Telegram admin config
    |--------------------------------------------------------------------------
    |
    | List of admin configurations to access bot
    |
    */

    'email' => env('ADMIN_EMAIL'),

    'id' => (int)env('ADMIN_ID', 0),
];
