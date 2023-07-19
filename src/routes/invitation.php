<?php

declare(strict_types=1);

use App\Http\Controllers\Invitation\InvitationController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:owner'])->group(
    function () {
        Route::get('invitation', [InvitationController::class, 'create'])->name('invitation.create');
        Route::post('invitation', [InvitationController::class, 'send'])->name('invitation.send');
    }
);
