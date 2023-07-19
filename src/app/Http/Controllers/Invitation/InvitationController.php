<?php

declare(strict_types=1);

namespace App\Http\Controllers\Invitation;

use App\Http\Controllers\Controller;
use App\Http\Requests\Invitation\SendInvitationRequest;
use App\Mail\Invitation\InvitationMail;
use App\Services\SignedUrlService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use Inertia\Response;

class InvitationController extends Controller
{
    public function __construct(private readonly SignedUrlService $signedUrlService)
    {
    }

    public function create(): Response
    {
        return Inertia::render('Invitation/Create');
    }

    public function send(SendInvitationRequest $request): RedirectResponse
    {
        $url = $this->signedUrlService->createRegistrationUrl();

        Mail::to($request->get('email'))->queue(new InvitationMail($url));

        return redirect()->route('invitation.create')->with(['message' => 'Invitation mail send']);
    }
}
