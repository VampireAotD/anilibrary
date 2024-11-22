<?php

declare(strict_types=1);

use App\Http\Controllers\Telegram\TelegramController;

Route::post('telegram/assign', [TelegramController::class, 'assign'])
     ->middleware(['telegram.signed', 'telegram.assigned'])
     ->name('telegram.assign');
