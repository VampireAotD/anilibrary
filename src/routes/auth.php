<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Telegram\LoginController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(
    function () {
        Route::get('login', [AuthenticatedSessionController::class, 'create'])
             ->name('login');

        Route::post('login', [AuthenticatedSessionController::class, 'store']);
    }
);

Route::middleware('auth')->group(
    function () {
        Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

        Route::group(
            ['name' => 'telegram', 'as' => 'telegram.', 'prefix' => 'telegram'],
            function () {
                Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)
                     ->middleware(['auth', 'signed', 'throttle:6,1'])
                     ->name('verification.verify');

                Route::post(
                    '/email/verification-notification',
                    [EmailVerificationNotificationController::class, 'store']
                )
                     ->middleware(['auth', 'throttle:6,1'])
                     ->name('verification.send');
                Route::middleware('telegram.validate-response')
                     ->get('login', [LoginController::class, 'store'])
                     ->name('login');
            }
        );

        Route::group(
            ['name' => 'email-verification', 'as' => 'verification.'],
            function () {
                Route::get('verify-email', EmailVerificationPromptController::class)->name('notice');

                Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
                     ->middleware(['signed', 'throttle:6,1'])
                     ->name('verify');

                Route::post(
                    'email/verification-notification',
                    [EmailVerificationNotificationController::class, 'store']
                )
                     ->middleware('throttle:6,1')
                     ->name('send');
            }
        );

        Route::group(
            ['name' => 'password', 'as' => 'password.'],
            function () {
                Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
                     ->name('confirm');

                Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

                Route::put('password', [PasswordController::class, 'update'])->name('update');
            }
        );

        Route::middleware('role:owner')->group(
            function () {
                Route::get('register', [RegisteredUserController::class, 'create'])
                     ->name('register');

                Route::post('register', [RegisteredUserController::class, 'store']);
            }
        );
    }
);