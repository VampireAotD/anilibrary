<?php

declare(strict_types=1);

use App\Http\Controllers\Invitation\InvitationController;
use Illuminate\Support\Facades\Route;

Route::apiResource('invitation', InvitationController::class)
     ->middleware('role:owner')
     ->except(['show'])
     ->names([
         'store'   => 'invitation.send',
         'update'  => 'invitation.accept',
         'destroy' => 'invitation.decline',
     ]);
