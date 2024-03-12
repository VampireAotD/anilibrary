<?php

declare(strict_types=1);

/** @var SergiX44\Nutgram\Nutgram $bot */

use App\Enums\Telegram\Actions\ActionEnum;
use App\Enums\Telegram\Buttons\CommandButtonEnum;
use App\Telegram\Callbacks\AnimeListCallback;
use App\Telegram\Callbacks\AnimeSearchCallback;
use App\Telegram\Callbacks\ViewAnimeCallback;
use App\Telegram\Commands\AnimeListCommand;
use App\Telegram\Commands\RandomAnimeCommand;
use App\Telegram\Commands\StartCommand;
use App\Telegram\Conversations\AddAnimeConversation;
use App\Telegram\Conversations\SearchAnimeConversation;
use App\Telegram\Middleware\BotAccessMiddleware;
use App\Telegram\Middleware\UserActivityMiddleware;
use App\Telegram\Middleware\UserStatusMiddleware;
use SergiX44\Nutgram\Conversations\Conversation;

/*
|--------------------------------------------------------------------------
| Nutgram Handlers
|--------------------------------------------------------------------------
|
| Here is where you can register telegram handlers for Nutgram. These
| handlers are loaded by the NutgramServiceProvider. Enjoy!
|
*/

// For DI in conversations
// @see https://github.com/nutgram/nutgram/issues/672#issuecomment-1973002698
// @see https://nutgram.dev/docs/usage/conversations#refreshing-deserialized-conversations
Conversation::refreshOnDeserialize();

// Apply middlewares
$bot->middlewares([BotAccessMiddleware::class, UserStatusMiddleware::class, UserActivityMiddleware::class]);

// Start
$bot->onCommand(ActionEnum::START_COMMAND->value, StartCommand::class);

// Add anime
$bot->onCommand(ActionEnum::START_ANIME_CONVERSATION->value, AddAnimeConversation::class);
$bot->onText(CommandButtonEnum::ADD_ANIME_BUTTON->value, AddAnimeConversation::class);

// Get random anime
$bot->onCommand(ActionEnum::RANDOM_ANIME_COMMAND->value, RandomAnimeCommand::class);
$bot->onText(CommandButtonEnum::RANDOM_ANIME_BUTTON->value, RandomAnimeCommand::class);

// List all anime
$bot->onCommand(ActionEnum::ANIME_LIST_COMMAND->value, AnimeListCommand::class);
$bot->onText(CommandButtonEnum::ANIME_LIST_BUTTON->value, AnimeListCommand::class);

// Search anime
$bot->onCommand(ActionEnum::START_SEARCH_ANIME_CONVERSATION->value, SearchAnimeConversation::class);
$bot->onText(CommandButtonEnum::ANIME_SEARCH_BUTTON->value, SearchAnimeConversation::class);

// Callbacks
$bot->onCallbackQueryData(ViewAnimeCallback::command(), ViewAnimeCallback::class);
$bot->onCallbackQueryData(AnimeListCallback::command(), AnimeListCallback::class);
$bot->onCallbackQueryData(AnimeSearchCallback::command(), AnimeSearchCallback::class);
