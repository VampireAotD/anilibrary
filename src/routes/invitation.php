<?php

declare(strict_types=1);

use App\Http\Controllers\Invitation\InvitationController;
use App\Http\Controllers\Invitation\ResendInvitationController;
use Illuminate\Support\Facades\Route;

Route::post('/invitation/{invitation}/resend', ResendInvitationController::class)
     ->middleware(['role:owner', 'invitation.accepted', 'throttle:1,1'])
     ->name('invitation.resend');

Route::apiResource('invitation', InvitationController::class)
     ->middleware('role:owner')
     ->except(['show'])
     ->names([
         'store'   => 'invitation.send',
         'update'  => 'invitation.accept',
         'destroy' => 'invitation.decline',
     ]);
