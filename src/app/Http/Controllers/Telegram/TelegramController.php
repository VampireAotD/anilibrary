<?php

declare(strict_types=1);

namespace App\Http\Controllers\Telegram;

use App\DTO\Service\Telegram\User\RegisterTelegramUserDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Telegram\AssignRequest;
use App\Services\TelegramUserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
        $dto = new RegisterTelegramUserDTO(
            (int) $request->get('id'),
            $request->get('first_name'),
            $request->get('last_name'),
            $request->get('username'),
        );

        try {
            $this->telegramUserService->createAndAttach($request->user(), $dto);
        } catch (Throwable $e) {
            Log::error('Failed to assign telegram user', [
                'exception_trace'   => $e->getTraceAsString(),
                'exception_message' => $e->getMessage(),
            ]);

            return back()->withErrors(['message' => $e->getMessage()]);
        }

        return back();
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function detach(Request $request): RedirectResponse
    {
        if (!$request->user()?->telegramUser()?->delete()) {
            return back()->withErrors(['message' => 'Could not revoke Telegram account']);
        }

        return back();
    }
}
