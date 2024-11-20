<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\DTO\Service\User\UserDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\Models\Invitation;
use App\Services\User\UserService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    public function __construct(private readonly UserService $userService)
    {
    }

    /**
     * Display the registration view.
     */
    public function create(Request $request): Response
    {
        /** @var Invitation $invitation */
        $invitation = $request->get('invitation');

        return Inertia::render('Auth/Register', [
            'email' => $invitation->email,
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(RegisterUserRequest $request): RedirectResponse
    {
        $user = $this->userService->register(UserDTO::fromArray($request->validated()));

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
