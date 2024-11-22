<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\DTO\Service\Invitation\InvitationDTO;
use App\Enums\Invitation\StatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AcquireRegistrationAccessRequest;
use App\Services\Invitation\InvitationService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

final class RegistrationAccessController extends Controller
{
    public function __construct(private readonly InvitationService $invitationService)
    {
    }

    public function create(): Response
    {
        return Inertia::render('Auth/RegistrationAccess/Create');
    }

    public function show(): Response
    {
        return Inertia::render('Auth/RegistrationAccess/Show');
    }

    public function store(AcquireRegistrationAccessRequest $request): RedirectResponse
    {
        $this->invitationService->create(
            new InvitationDTO($request->post('email'), StatusEnum::PENDING)
        );

        return to_route('registration_access.await');
    }
}
