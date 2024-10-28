<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\DTO\Service\User\UserDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\Services\User\UserService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use Psr\SimpleCache\InvalidArgumentException;

class RegisteredUserController extends Controller
{
    public function __construct(private readonly UserService $userService)
    {
    }

    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException|InvalidArgumentException
     */
    public function store(RegisterUserRequest $request): RedirectResponse
    {
        $user = $this->userService->updateOrCreate(UserDTO::fromArray($request->validated()));

        event(new Registered($user));

        Auth::login($user);

        Cache::delete(hash('sha256', $user->email));

        return redirect(route('dashboard', absolute: false));
    }
}
