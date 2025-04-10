<?php

declare(strict_types=1);

use App\Http\Controllers\Invitation\InvitationController;
use App\Http\Controllers\Invitation\ResendInvitationController;
use Glhd\Gretel\Routing\ResourceBreadcrumbs;
use Illuminate\Support\Facades\Route;

Route::post('/invitation/{invitation}/resend', ResendInvitationController::class)
     ->middleware(['role:owner', 'invitation.status:accepted', 'throttle:1,1'])
     ->name('invitation.resend');

Route::apiResource('invitation', InvitationController::class)
     ->middleware('role:owner')
     ->middlewareFor(methods: 'update', middleware: 'invitation.status:pending')
     ->middlewareFor(methods: 'destroy', middleware: 'invitation.not_declined')
     ->except(['show'])
     ->names([
         'store'   => 'invitation.send',
         'update'  => 'invitation.accept',
         'destroy' => 'invitation.decline',
     ])
     ->breadcrumbs(static fn(ResourceBreadcrumbs $breadcrumbs) => $breadcrumbs->index('Invitations', 'dashboard'));
