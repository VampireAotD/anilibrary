<?php

declare(strict_types=1);

namespace App\Http\Controllers\Telegram;

use App\DTO\Service\Telegram\CreateUserDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Telegram\AssignRequest;
use App\Services\TelegramUserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

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
        $dto = new CreateUserDTO(
            (int) $request->get('id'),
            $request->get('first_name'),
            $request->get('last_name'),
            $request->get('username'),
        );

        $this->telegramUserService->createAndAttach($dto, $request->user());

        return back();
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function detach(Request $request): RedirectResponse
    {
        if (!$request->user()?->telegramUser()?->update(['user_id' => null])) {
            return back()->withErrors(['message' => 'Could not revoke Telegram account']);
        }

        return back();
    }
}
