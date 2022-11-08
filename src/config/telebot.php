<?php

use App\Telegram\Commands\StartCommand;
use App\Telegram\Handlers\AddNewAnimeHandler;
use App\Telegram\Handlers\CallbackQueryHandler;
use App\Telegram\Handlers\CommandHandler;
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
                // 'allowed_updates'   => ["message", "edited_channel_post", "callback_query"]
            ],

            'poll' => [
                // 'limit'             => 100,
                // 'timeout'           => 0,
                // 'allowed_updates'   => ["message", "edited_channel_post", "callback_query"]
            ],

            'handlers' => [
                // Middlewares
                new UserStatusMiddleware(),
                new BotAccessMiddleware(),
                new UserActivityMiddleware(),

                // Commands
                StartCommand::class,

                // Handlers,
                CommandHandler::class,
                CallbackQueryHandler::class,
                AddNewAnimeHandler::class,
            ],
        ],
    ],
];
