<?php

declare(strict_types=1);

namespace App\Http\Controllers\Telegram;

use App\DTO\Service\Telegram\User\TelegramUserDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Telegram\AssignRequest;
use App\Services\Telegram\TelegramUserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Throwable;

class TelegramController extends Controller
{
    public function __construct(private readonly TelegramUserService $telegramUserService)
    {
    }

    /**
     * Assign telegram user to user that send request
     */
    public function assign(AssignRequest $request): RedirectResponse
    {
        try {
            $this->telegramUserService->assign($request->user(), TelegramUserDTO::fromArray($request->validated()));

            return back();
        } catch (Throwable $e) {
            Log::error('Failed to assign telegram user', [
                'exception_trace'   => $e->getTraceAsString(),
                'exception_message' => $e->getMessage(),
            ]);

            return back()->withErrors(['message' => $e->getMessage()]);
        }
    }
}
