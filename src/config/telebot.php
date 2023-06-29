<?php

declare(strict_types=1);

use App\Telegram\Middlewares\BotAccessMiddleware;
use App\Telegram\Middlewares\UserActivityMiddleware;
use App\Telegram\Middlewares\UserStatusMiddleware;

return [
    /*-------------------------------------------------------------------------
    | Default Bot Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the bots you wish to use as
    | your default bot for regular use.
    |
    */

    'default' => 'anilibrary',

    /*-------------------------------------------------------------------------
    | Whitelist
    |--------------------------------------------------------------------------
    |
    | A list of telegram ids that have access to bot, separated by coma
    |
    */

    'whitelist' => env('TELEGRAM_WHITELIST', ''),

    /*-------------------------------------------------------------------------
    | Your Telegram Bots
    |--------------------------------------------------------------------------
    | You may use multiple bots. Each bot that you own should be configured here.
    |
    | See the docs for parameters specification:
    | https://westacks.github.io/telebot/#/configuration
    |
    */

    'bots' => [
        'anilibrary' => [
            'token'      => env('TELEGRAM_BOT_TOKEN'),
            'name'       => env('TELEGRAM_BOT_NAME', null),
            'api_url'    => env('TELEGRAM_API_URL', 'https://api.telegram.org/bot{TOKEN}/{METHOD}'),
            'exceptions' => true,
            'async'      => false,

            'webhook' => [
                // 'url'               => env('TELEGRAM_BOT_WEBHOOK_URL', env('APP_URL').'/telebot/webhook/bot/'.env('TELEGRAM_BOT_TOKEN')),,
                // 'certificate'       => env('TELEGRAM_BOT_CERT_PATH', storage_path('app/ssl/public.pem')),
                // 'ip_address'        => '8.8.8.8',
                // 'max_connections'   => 40,
                'allowed_updates' => ['message', 'callback_query', 'my_chat_member'],
            ],

            'poll' => [
                // 'limit'             => 100,
                // 'timeout'           => 0,
                'allowed_updates' => ['message', 'callback_query', 'my_chat_member'],
            ],

            'handlers' => [
                // Middlewares
                (new UserStatusMiddleware())(...),
                (new BotAccessMiddleware())(...),
                (new UserActivityMiddleware())(...),

                // Commands
                \App\Telegram\Commands\StartCommand::class,

                // Callbacks
                \App\Telegram\Callbacks\ViewAnimeCallback::class,
                \App\Telegram\Callbacks\AnimeListCallback::class,
                \App\Telegram\Callbacks\AnimeSearchCallback::class,

                // Handlers
                \App\Telegram\Handlers\MessageHandler::class,
                \App\Telegram\Handlers\AddAnimeHandler::class,
                \App\Telegram\Handlers\RandomAnimeHandler::class,
                \App\Telegram\Handlers\AnimeListHandler::class,
                \App\Telegram\Handlers\AnimeSearchHandler::class,
            ],
        ],
    ],
];
